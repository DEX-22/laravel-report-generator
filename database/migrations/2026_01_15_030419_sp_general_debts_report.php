<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE PROCEDURE sp_general_debts_report(IN start_date DATE,IN end_date DATE)
                BEGIN
                        # CREDIT CARD REPORT
                        select rep_cc.id as report_id,
                                subs.full_name,subs.document,subs.email,subs.phone,
                                'Tarjeta de crédito' AS tipo_deuda,
                                'No registra' AS situacion,
                                'No registra' as atraso,
                                rep_cc.bank as entidad,
                                rep_cc.line - rep_cc.used as monto_total,
                                rep_cc.line as linea_total, 
                                rep_cc.used as linea_usada,
                                sub_rep.created_at as fecha_reporte,
                                'pendiente' as estado

                        from subscription_reports as sub_rep
                        join subscriptions as subs on subs.id = sub_rep.subscription_id 
                        left join report_credit_cards as rep_cc on rep_cc.subscription_report_id = sub_rep.id
                        WHERE sub_rep.created_at between start_date AND end_date
                        UNION ALL
                        # LOANS REPORT
                        select rep_loa.id as report_id,
                                subs.full_name,subs.document,subs.email,subs.phone,
                                'Préstamo' AS tipo_deuda,
                                rep_loa.status AS situacion,
                                rep_loa.expiration_days as atraso,
                                rep_loa.bank as entidad,
                                rep_loa.amount as monto_total,
                                'NO aplica' as linea_total, 
                                'NO aplica' as linea_usada,
                                sub_rep.created_at as fecha_reporte,
                                'pendiente' as estado
                        from subscription_reports as sub_rep
                        join subscriptions as subs on subs.id = sub_rep.subscription_id 
                        join report_loans as rep_loa on rep_loa.subscription_report_id = sub_rep.id
                        WHERE sub_rep.created_at between start_date AND end_date
                        UNION ALL
                        # OTHER DEBTS REPORT
                        select rep_od.id as report_id,
                                subs.full_name,subs.document,subs.email,subs.phone,
                                'Otra deuda' AS tipo_deuda,
                                'No registra' AS situacion,
                                rep_od.expiration_days as atraso,
                                rep_od.entity as entidad,
                                rep_od.amount as monto_total,
                                'NO aplica' as linea_total, 
                                'NO aplica' as linea_usada,
                                sub_rep.created_at as fecha_reporte,
                                'pendiente' as estado
                        from subscription_reports as sub_rep
                        join subscriptions as subs on subs.id = sub_rep.subscription_id 
                        join report_other_debts as rep_od on rep_od.subscription_report_id = sub_rep.id
                        WHERE sub_rep.created_at between start_date AND end_date;
                END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_general_debts_report;");
    }
};
