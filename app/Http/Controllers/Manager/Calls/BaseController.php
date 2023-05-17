<?php

namespace App\Http\Controllers\Manager\Calls;

use App\Http\Controllers\Controller;
use App\Services\Manager\Calls\Service;

class BaseController extends Controller
{
    public object $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
