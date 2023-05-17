<?php

namespace App\Http\Controllers\Manager\ExpensesObject;

use App\Http\Controllers\Controller;
use App\Models\ExpensesObject;

class EditController extends Controller
{

    public function __invoke(ExpensesObject $expense)
    {
        return view('manager.expenses_object.edit',compact('expense'));
    }
}
