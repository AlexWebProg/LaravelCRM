<?php

namespace App\Http\Controllers\Manager\ExpensesAllPersonal;

use App\Models\User;

class ManagersIndexController extends BaseController
{
    public function __invoke()
    {
        $managers = User::where('is_active', 1)
            ->where('type', 1)
            ->get();

        return view('manager.expenses_all_personal.managers_index', compact('managers'));
    }
}
