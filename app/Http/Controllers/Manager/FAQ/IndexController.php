<?php

namespace App\Http\Controllers\Manager\FAQ;

use App\Http\Controllers\Controller;
use App\Models\FAQ;

class IndexController extends Controller
{
    public function __invoke()
    {
        $faqs = FAQ::all();
        return view('manager.faq.index', compact('faqs'));
    }
}
