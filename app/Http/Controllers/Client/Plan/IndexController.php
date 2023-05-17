<?php

namespace App\Http\Controllers\Client\Plan;

use App\Models\Stat;
use App\Http\Controllers\Controller;
use App\Models\User;

class IndexController extends Controller
{
    public function __invoke()
    {
        User::where('id', auth()->user()->id)->update(['plan_updated' => null]);
        Stat::create(['user_id' => auth()->user()->id, 'action' => 'План работ']);
        return view('client.plan.index');
    }
}
