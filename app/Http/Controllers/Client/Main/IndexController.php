<?php

namespace App\Http\Controllers\Client\Main;

use App\Models\Stat;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __invoke()
    {
        Stat::create(['user_id' => auth()->user()->id, 'action' => 'Рабочий стол']);
        return view('client.main.index');
    }
}
