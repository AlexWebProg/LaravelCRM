<?php

namespace App\Http\Controllers\Manager\ExpensesAllPersonal;

use App\Models\User;

class ExpensesIndexController extends BaseController
{
    public function __invoke(User $manager)
    {
        $arExpenses = $this->service->getAllManagerExpenses($manager->id);
        $strBalance = $this->service->getExpensesBalance($arExpenses);
        return view('manager.expenses_all_personal.expenses_index', compact('manager', 'arExpenses', 'strBalance'));
    }
}
