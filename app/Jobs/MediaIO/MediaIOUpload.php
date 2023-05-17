<?php

namespace App\Jobs\MediaIO;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

// Загружает файл в MediaIO
class MediaIOUpload extends MediaIOBase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
            $response = Http::withToken(env('MEDIA_IO_API_KEY'))->post($this->base_url.'import/url', [
                'url' => env('APP_URL') . '/storage/' . $this->src,
                'file_name' => basename($this->src),
            ])->json();
            if (!empty($response['data']['id'])) {
                // Если файл поставлен на загрузку, запускаем проверку его статуса
                MediaIOCheckStatus::dispatch($response['data']['id'],$this->src)
                    ->delay(now()->addMinutes(5));
            } else {
                $this->release(300);
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
