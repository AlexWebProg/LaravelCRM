<?php

namespace App\Http\Controllers\Manager\ExpensesPersonal;

class CreateController extends BaseController
{
    public function __invoke()
    {
        $expense_form_data = $this->service->getExpensePersonaFormData();

        return view('manager.expenses_personal.create',compact('expense_form_data'));
    }
}
