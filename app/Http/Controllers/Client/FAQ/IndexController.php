<?php

namespace App\Http\Controllers\Client\FAQ;

use App\Models\Stat;
use App\Models\FAQ;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __invoke()
    {
        Stat::create(['user_id' => auth()->user()->id, 'action' => 'Частые вопросы']);
        $faqs = FAQ::where('is_active',1)
            ->orderBy('sort','asc')
            ->get();
        return view('client.faq.index',compact('faqs'));
    }
}
