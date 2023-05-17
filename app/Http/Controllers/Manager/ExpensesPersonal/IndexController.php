<?php

namespace App\Http\Controllers\Manager\ExpensesPersonal;

class IndexController extends BaseController
{
    public function __invoke()
    {
        $arExpenses = $this->service->getAllManagerExpenses(auth()->user()->id);
        $strBalance = $this->service->getExpensesBalance($arExpenses);
        return view('manager.expenses_personal.index', compact('arExpenses', 'strBalance'));
    }
}
