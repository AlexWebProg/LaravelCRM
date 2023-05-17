<?php

namespace App\Http\Controllers\Client\Partner;

use App\Models\Stat;
use App\Models\Partner;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __invoke()
    {
        Stat::create(['user_id' => auth()->user()->id, 'action' => 'Партнёры']);
        $partners = Partner::where('is_active',1)
            ->orderBy('sort','asc')
            ->get();
        return view('client.partner.index',compact('partners'));
    }
}
