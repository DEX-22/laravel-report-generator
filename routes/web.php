<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::controller(ReportController::class)->group(function(){

    Route::get('/','index');
    Route::post('/report','getReportByRange')->name('general.report.post');
    Route::get('/report/download/{folder}/{filename}','downloadSavedReport')->name('general.report.download')->middleware('signed');
});
