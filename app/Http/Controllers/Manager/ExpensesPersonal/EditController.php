<?php

namespace App\Http\Controllers\Manager\ExpensesPersonal;
use App\Models\ExpensesPersonal;

class EditController extends BaseController
{
    public function __invoke(ExpensesPersonal $expense)
    {
        $expense_form_data = $this->service->getExpensePersonaFormData();

        if ($expense?->manager_id !== auth()->user()->id) {
            return redirect()
                ->route('manager.expenses_personal.index')
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'Этот расход недоступен'
                ]);
        } else {
            return view('manager.expenses_personal.edit', compact('expense','expense_form_data'));
        }

    }
}
