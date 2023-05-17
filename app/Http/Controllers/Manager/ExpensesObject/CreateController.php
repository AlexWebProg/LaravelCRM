<?php

namespace App\Http\Controllers\Manager\ExpensesObject;

use App\Http\Controllers\Controller;
use App\Models\User;

class CreateController extends Controller
{
    public function __invoke($client_id = 0)
    {
        $client = '';
        if (!empty($client_id)) {
            $client = User::find($client_id);
        }
        return view('manager.expenses_object.create',compact('client_id','client'));
    }
}
