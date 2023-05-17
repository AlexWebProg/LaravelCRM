<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;
use App\Services\Manager\Client\Service;

class BaseController extends Controller
{
    public object $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
