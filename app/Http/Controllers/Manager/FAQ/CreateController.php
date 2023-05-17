<?php

namespace App\Http\Controllers\Manager\FAQ;

use App\Http\Controllers\Controller;
use App\Models\FAQ;

class CreateController extends Controller
{
    public function __invoke()
    {
        $sort_init = 10;
        $last_faq = FAQ::orderBy('sort','desc')
                ->limit(1)
                ->first();
        if (!empty($last_faq->sort)) {
            $sort_init = $last_faq->sort + 10;
        }
        return view('manager.faq.create', compact('sort_init'));
    }
}
