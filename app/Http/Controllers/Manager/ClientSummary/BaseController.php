<?php

namespace App\Http\Controllers\Manager\ClientSummary;

use App\Http\Controllers\Controller;
use App\Services\Manager\ClientSummary\Service;

class BaseController extends Controller
{
    public object $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
