<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ReportByRangeRequest;
use App\Jobs\GenerateAndDownloadReport;
use App\Services\IReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        protected IReportService $reportService
    ) {
    }

    public function index(Request $request)
    {
        return view('welcome');
    }

    public function getReportByRange(ReportByRangeRequest $request)
    {
        GenerateAndDownloadReport::dispatch($this->reportService, $request->start, $request->end);

        return response()->json();
    }

    public function downloadSavedReport($folder, $filename)
    {
        $url = storage_path("app/private/{$folder}/{$filename}");

        if (! file_exists($url)) {
            abort(404);
        }

        return response()->download($url)->deleteFileAfterSend(true);
    }
}
