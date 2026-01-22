<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Events\FailedDownloadEvent;
use App\Events\FinishDownloadEvent;
use App\Exports\GeneralReportExport;
use App\Services\IReportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class GenerateAndDownloadReport implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected IReportService $reportService,
        public string $start,
        public string $end
    ) {
    }

    public function failed(Throwable $exception): void
    {
        broadcast(new FailedDownloadEvent());
        Log::error('[ERR 001] Report generation failed: ' . $exception->getMessage());
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $folder = 'reports';
        $fileName = "General report ({$this->start}-{$this->end}).xlsx";

        $result = Excel::store(new GeneralReportExport($this->reportService, $this->start, $this->end), "{$folder}/{$fileName}", 'local');

        if ($result) {
            $signedUrl = URL::temporarySignedRoute('general.report.download', now()->addMinutes(10), [
                'folder' => $folder,
                'filename' => $fileName,
            ]);

            broadcast(new FinishDownloadEvent($signedUrl));
        } else {
            broadcast(new FailedDownloadEvent());
        }
    }
}
