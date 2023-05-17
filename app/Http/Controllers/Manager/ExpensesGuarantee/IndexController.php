<?php

namespace App\Http\Controllers\Manager\ExpensesGuarantee;

use App\Http\Controllers\Controller;
use App\Services\Manager\ExpensesObject\ExpensesObjectService;

class IndexController extends Controller
{
    public object $expenses_object_service;

    public function __construct(ExpensesObjectService $expenses_object_service)
    {
        $this->expenses_object_service = $expenses_object_service;
    }

    public function __invoke()
    {
        $arExpenses = $this->expenses_object_service->getObjectExpenses(null);
        $client_id = 0;
        $expenses = $arExpenses['expenses'];
        $expensesTotal = $arExpenses['expensesTotal'];
        return view('manager.expenses_guarantee.index', compact('client_id','expenses', 'expensesTotal'));
    }
}
