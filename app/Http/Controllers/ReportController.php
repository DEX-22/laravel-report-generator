<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportByRangeRequest;
use App\Services\IReportService;
use Illuminate\Http\Request;


class ReportController extends Controller
{
    public function __construct(
        protected IReportService $service
    )
    {}
        
    public function index(Request $request){
        return view('welcome');
    }
    public function getReportByRange(ReportByRangeRequest $request){
        $data = $this->service->getInfoByCreationDate($request->start,$request->end);

        return response()->json($data);
    }
}
