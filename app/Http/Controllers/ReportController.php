<?php

namespace App\Http\Controllers;

use App\Exports\GeneralReportExport;
use App\Http\Requests\ReportByRangeRequest;
use App\Services\IReportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{       
    public function index(Request $request){
        return view('welcome');
    }
    public function getReportByRange(ReportByRangeRequest $request){

        return Excel::download(new GeneralReportExport($request->start,$request->end), 'General report.xlsx');
    }
}
