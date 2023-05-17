<?php

namespace App\Http\Controllers\Manager\Manager;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __invoke($is_admin=0)
    {
        $managers = User::where('is_active', 1)
            ->where('type', 1)
            ->where('is_admin', $is_admin)
            ->get();

        return view('manager.manager.index', compact('managers'));
    }
}
