<?php

namespace App\Http\Controllers\Manager\ExpensesMatReport;

use App\Http\Controllers\Controller;
use App\Services\Manager\ExpensesMatReport\Service;

class BaseController extends Controller
{
    public object $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
