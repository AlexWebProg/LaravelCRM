<?php

namespace App\Http\Controllers\Manager\ExpensesAllPersonal;
use App\Models\User;

class CreateController extends BaseController
{
    public function __invoke(User $manager)
    {
        $expense_form_data = $this->service->getExpensePersonaFormData($manager);
        return view('manager.expenses_all_personal.create',compact('manager','expense_form_data'));
    }
}
