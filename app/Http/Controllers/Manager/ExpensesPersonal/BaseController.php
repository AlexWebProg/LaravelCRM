<?php

namespace App\Http\Controllers\Manager\ExpensesPersonal;

use App\Http\Controllers\Controller;
use App\Services\Manager\ExpensesPersonal\Service;

class BaseController extends Controller
{
    public object $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
