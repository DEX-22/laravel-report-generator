<?php

declare(strict_types=1);

namespace App\Services;

use Generator;
use Illuminate\Support\Facades\DB;

interface IReportService
{
    public function getGeneralDebtsReport(string $start_date, string $end_date): Generator;
}

class ReportService implements IReportService
{
    public function getGeneralDebtsReport(string $start_date, string $end_date): Generator
    {
        foreach ($this->getCreditCardReportCursor($start_date, $end_date) as $row) {
            yield $row;
        }
        foreach ($this->getLoansReportCursor($start_date, $end_date) as $row) {
            yield $row;
        }
        foreach ($this->getOthersDebtsReportCursor($start_date, $end_date) as $row) {
            yield $row;
        }
    }

    private function getCreditCardReportCursor(string $start_date, string $end_date)
    {

        $subRep = 'sub_rep';
        $subs = 'subs';
        $repCc = 'rep_cc';
        return DB::table("subscription_reports as {$subRep}")
            ->join("subscriptions as {$subs}", "{$subs}.id", '=', "{$subRep}.subscription_id")
            ->leftJoin("report_credit_cards as {$repCc}", "{$repCc}.subscription_report_id", '=', "{$subRep}.id")
            ->whereBetween("{$subRep}.created_at", [$start_date, $end_date])
            ->selectRaw(
                "{$repCc}.id as report_id,
            {$subs}.full_name,{$subs}.document,{$subs}.email,{$subs}.phone,
            {$repCc}.bank as company,
            'Tarjeta de crédito' AS debt_type,
            '' AS situation,
            '' as late_payment,
            {$repCc}.bank as entity,
            {$repCc}.line - {$repCc}.used as total_amount,
            {$repCc}.line as total_line, 
            {$repCc}.used as total_used,
            DATE({$subRep}.created_at) as report_date,
            'normal' as general_status"
            )
            ->cursor();
    }

    private function getLoansReportCursor(string $start_date, string $end_date)
    {

        $subRep = 'sub_rep';
        $subs = 'subs';
        $repLoa = 'rep_loa';
        return DB::table("subscription_reports as {$subRep}")
            ->join("subscriptions as {$subs}", "{$subs}.id", '=', "{$subRep}.subscription_id")
            ->leftJoin("report_loans as {$repLoa}", "{$repLoa}.subscription_report_id", '=', "{$subRep}.id")
            ->whereBetween("{$subRep}.created_at", [$start_date, $end_date])
            ->selectRaw(
                "{$repLoa}.id as report_id,
                {$subs}.full_name,{$subs}.document,{$subs}.email,{$subs}.phone,
                {$repLoa}.bank as company,
                'Préstamo' AS debt_type,
                {$repLoa}.status AS situation,
                {$repLoa}.expiration_days as late_payment,
                {$repLoa}.bank as entity,
                {$repLoa}.amount as total_amount,
                '' as total_line, 
                '' as total_used,
                DATE({$subRep}.created_at) as report_date,
                IF({$repLoa}.expiration_days > 0 , 'perdida' , 'normal') as general_status"
            )
            ->cursor();
    }

    private function getOthersDebtsReportCursor(string $start_date, string $end_date)
    {

        $subRep = 'sub_rep';
        $subs = 'subs';
        $repOd = 'rep_od';
        return DB::table("subscription_reports as {$subRep}")
            ->join("subscriptions as {$subs}", "{$subs}.id", '=', "{$subRep}.subscription_id")
            ->leftJoin("report_other_debts as {$repOd}", "{$repOd}.subscription_report_id", '=', "{$subRep}.id")
            ->whereBetween("{$subRep}.created_at", [$start_date, $end_date])
            ->selectRaw("{$repOd}.id as report_id,
                        {$subs}.full_name,{$subs}.document,{$subs}.email,{$subs}.phone,
                        {$repOd}.entity as company,
                        'Otra deuda' AS tipo_deuda,
                        '' AS situacion,
                        {$repOd}.expiration_days as late_payment,
                        {$repOd}.entity as entity,
                        {$repOd}.amount as total_amount,
                        '' as total_line, 
                        '' as total_used,
                        DATE({$subRep}.created_at) as report_date,
                        IF({$repOd}.expiration_days > 0 , 'perdida' , 'normal') as general_status")
            ->cursor();

    }
}
