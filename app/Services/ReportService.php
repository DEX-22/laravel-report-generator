<?php

namespace App\Services;

use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

interface IReportService{
    public function getInfoByCreationDate(string $start,string $end);    
}

class ReportService implements IReportService{
    public function getInfoByCreationDate(string $start,string $end){
        $data = DB::select("CALL sp_general_debts_report(?,?)",[$start,$end]);
        return $data;
    }
}