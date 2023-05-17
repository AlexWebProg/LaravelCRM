<?php

namespace App\Http\Controllers\Manager\Manager;

use App\Http\Controllers\Controller;
use App\Services\Manager\Manager\Service;

class BaseController extends Controller
{
    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
