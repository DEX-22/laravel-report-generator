<?php

namespace App\Http\Controllers;

use App\Exports\GeneralReportExport;
use App\Http\Requests\ReportByRangeRequest;
use App\Jobs\GenerateAndDownloadReport;
use App\Services\IReportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{       
    public function index(Request $request){
        return view('welcome');
    }
    public function getReportByRange(ReportByRangeRequest $request){
        GenerateAndDownloadReport::dispatch($request->start,$request->end);

        return response();
    }
    public function downloadSavedReport($folder,$fileName){
        $url = storage_path("$folder/$fileName");
        
        if (!file_exists($url)) {
            abort(404);
        }

        return response()->download($url)->deleteFileAfterSend(true);    
    }
}
