<?php

namespace App\Http\Controllers\Manager\FAQ;

use App\Http\Controllers\Controller;
use App\Models\FAQ;

class EditController extends Controller
{
    public function __invoke(FAQ $faq)
    {
        return view('manager.faq.edit', compact('faq'));
    }
}
