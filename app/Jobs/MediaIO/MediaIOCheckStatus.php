<?php

namespace App\Jobs\MediaIO;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

// Проверяет статус загруженного файла в MediaIO
class MediaIOCheckStatus extends MediaIOBase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $id;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 30;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($media_io_id,$file_src)
    {
        $this->id = $media_io_id;
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
            $response = Http::withToken(env('MEDIA_IO_API_KEY'))->get($this->base_url.'tasks/'.$this->id)->json();
            if (!empty($response['data']['status']) && $response['data']['status'] === 'success' && !empty($response['data']['operation'])) {
                if ($response['data']['operation'] === 'import/url') {
                    // Если файл загружен, запускаем сжатие
                    MediaIOCompress::dispatch($response['data']['id'],$this->src);
                } elseif ($response['data']['operation'] === 'convert') {
                    // Запускаем экспорт
                    MediaIOExport::dispatch($response['data']['id'],$this->src);
                } elseif ($response['data']['operation'] === 'export/url' && !empty($response['data']['result']['files'][0]['url'])) {
                    copy($response['data']['result']['files'][0]['url'], $this->src);
                } else {
                    $this->release(300);
                }
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
