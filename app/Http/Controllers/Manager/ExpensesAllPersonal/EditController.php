<?php

namespace App\Http\Controllers\Manager\ExpensesAllPersonal;
use App\Models\ExpensesPersonal;
use App\Models\User;

class EditController extends BaseController
{
    public function __invoke(User $manager, ExpensesPersonal $expense)
    {
        $expense_form_data = $this->service->getExpensePersonaFormData($manager);
        return view('manager.expenses_all_personal.edit',compact('manager','expense', 'expense_form_data'));
    }
}
