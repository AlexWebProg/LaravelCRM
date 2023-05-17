<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Tinify\Exception;
use Tinify\ResultMeta;
use Tinify\Result;
use Tinify\Source;
use Tinify\Client;
use Tinify\Tinify;

// Сжимает Фото
class CompressImage implements ShouldQueue
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
            // Use the Tinify API client.
            \Tinify\setKey(env('TINYPNG_API_KEY'));
            $source = \Tinify\fromFile(storage_path('app/public/' . $this->src));
            $source->toFile(storage_path('app/public/' . $this->src));
        } catch(\Tinify\AccountException $e) {
            // Verify your API key and account limit.
            $this->failed($e);
        } catch(\Tinify\ClientException $e) {
            // Check your source image and request options.
            $this->failed($e);
        } catch(\Tinify\ServerException $e) {
            // Temporary issue with the Tinify API.
            $this->failed($e);
        } catch(\Tinify\ConnectionException $e) {
            // A network connection error occurred.
            $this->failed($e);
        } catch(Exception $e) {
            // Something else went wrong, unrelated to the Tinify API.
            $this->failed($e);
        }
    }

    public function failed($exception)
    {
        $exception->getMessage();
    }

}
