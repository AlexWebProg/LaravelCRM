<?php

namespace App\Http\Controllers\Manager\Manager;

use App\Http\Controllers\Controller;

class CreateController extends Controller
{
    public function __invoke($is_admin=0)
    {
        return view('manager.manager.create');
    }
}
