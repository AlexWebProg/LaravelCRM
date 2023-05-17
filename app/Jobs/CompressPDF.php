<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Ilovepdf\Ilovepdf;

// Сжимает PDF
class CompressPDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $src;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 10;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file_src)
    {
        $this->src = $file_src;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $folder = pathinfo($this->src, PATHINFO_DIRNAME);
            $ilovepdf = new Ilovepdf(env('I_LOVE_PDF_PUBLIC_KEY'),env('I_LOVE_PDF_SECRET_KEY'));
            $remainingFiles = $ilovepdf->getRemainingFiles();
            if ($remainingFiles > 0) {
                $myTask = $ilovepdf->newTask('compress');
                $myTask->addFile(storage_path('app/public/' . $this->src));
                $myTask->execute();
                $myTask->download(storage_path('app/public/' . $folder));
            }
        } catch (\Exception $e) {
            $this->failed($e);
        }
    }

    public function failed($exception)
    {
        $exception->getMessage();
    }

}
