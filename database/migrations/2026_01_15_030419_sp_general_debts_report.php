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
                                        rep_cc.bank as company,
                                        'Tarjeta de crédito' AS debt_type,
                                        '' AS situation,
                                        '' as late_payment,
                                        rep_cc.bank as entity,
                                        rep_cc.line - rep_cc.used as total_amount,
                                        rep_cc.line as total_line, 
                                        rep_cc.used as total_used,
                                        DATE(sub_rep.created_at) as report_date,
                                        'normal' as general_status

                        from subscription_reports as sub_rep
                        join subscriptions as subs on subs.id = sub_rep.subscription_id 
                        left join report_credit_cards as rep_cc on rep_cc.subscription_report_id = sub_rep.id
                        WHERE sub_rep.created_at between start_date AND end_date
                        UNION ALL
                        # LOANS REPORT
                        select rep_loa.id as report_id,
                                        subs.full_name,subs.document,subs.email,subs.phone,
                                        rep_loa.bank as company,
                                        'Préstamo' AS debt_type,
                                        rep_loa.status AS situation,
                                        rep_loa.expiration_days as late_payment,
                                        rep_loa.bank as entity,
                                        rep_loa.amount as total_amount,
                                        '' as total_line, 
                                        '' as total_used,
                                        DATE(sub_rep.created_at) as report_date,
                                        IF(rep_loa.expiration_days > 0 , 'perdida' , 'normal') as general_status
                        from subscription_reports as sub_rep
                        join subscriptions as subs on subs.id = sub_rep.subscription_id 
                        join report_loans as rep_loa on rep_loa.subscription_report_id = sub_rep.id
                        WHERE sub_rep.created_at between start_date AND end_date
                        UNION ALL
                        # OTHER DEBTS REPORT
                        select rep_od.id as report_id,
                                        subs.full_name,subs.document,subs.email,subs.phone,
                                        rep_od.entity as company,
                                        'Otra deuda' AS tipo_deuda,
                                        '' AS situacion,
                                        rep_od.expiration_days as late_payment,
                                        rep_od.entity as entity,
                                        rep_od.amount as total_amount,
                                        '' as total_line, 
                                        '' as total_used,
                                        DATE(sub_rep.created_at) as report_date,
                                        IF(rep_od.expiration_days > 0 , 'perdida' , 'normal') as general_status
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
