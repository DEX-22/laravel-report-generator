<?php

namespace App\Http\Controllers;

use App\Exports\GeneralReportExport;
use App\Http\Requests\ReportByRangeRequest;
use App\Jobs\GenerateAndDownloadReport;
use App\Services\IReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{       
    public function index(Request $request){
        return view('welcome');
    }
    public function getReportByRange(ReportByRangeRequest $request){
        GenerateAndDownloadReport::dispatch($request->start,$request->end);

        return response()->json();
    }
    public function downloadSavedReport($folder,$filename){
        $url = storage_path("app/private/$folder/$filename");
        Log::info("url: $folder/$filename",[$url]);

        if (!file_exists($url)) {
            abort(404);
        }

        return response()->download($url)->deleteFileAfterSend(true);    
    }
}
