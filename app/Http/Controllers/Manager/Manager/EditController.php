<?php

namespace App\Http\Controllers\Manager\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Manager\ExpensesPersonal\Service;

class EditController extends Controller
{
    public object $expenses_service;

    public function __construct(Service $expenses_service)
    {
        $this->expenses_service = $expenses_service;
    }

    public function __invoke(User $manager, $action_type = 'main_form')
    {
        // Отчёт по расходам
        if ($action_type === 'expenses') {
            $arExpenses = $this->expenses_service->getAllManagerExpenses($manager->id);
            $strBalance = $this->expenses_service->getExpensesBalance($arExpenses);
            return view('manager.manager.edit.expenses', compact('manager', 'arExpenses', 'strBalance'));
        }

        return view('manager.manager.edit.'.$action_type, compact('manager'));
    }
}
