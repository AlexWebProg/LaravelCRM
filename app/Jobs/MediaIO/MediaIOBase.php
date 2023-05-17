<?php
namespace App\Jobs\MediaIO;

use Illuminate\Support\Facades\Http;

class MediaIOBase
{
    protected string $base_url = 'https://api.media.io/v2/';
    protected string $src;
}
