<?php

namespace App\Jobs;

use App\Events\FailedDownloadEvent;
use App\Events\FinishDownloadEvent;
use App\Exports\GeneralReportExport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;

class GenerateAndDownloadReport implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $start,
        public string $end
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $folder = "reports";
        $fileName = "General report ({$this->start}-{$this->end}).xlsx";
        // $url = storage_path("$folder/$fileName");
        $result = Excel::store(new GeneralReportExport($this->start,$this->end), "$folder/$fileName",'local');
        
        if($result){
            $signedUrl = URL::temporarySignedRoute('general.report.download',now()->addMinutes(10),[
                'folder' => $folder, 'filename' => $fileName
            ]); 

            broadcast(new FinishDownloadEvent($signedUrl));
        }else{
            broadcast(new FailedDownloadEvent());
        }
    } 
}
