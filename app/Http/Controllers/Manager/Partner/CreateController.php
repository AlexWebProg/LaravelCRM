<?php

namespace App\Http\Controllers\Manager\Partner;

use App\Http\Controllers\Controller;
use App\Models\Partner;

class CreateController extends Controller
{
    public function __invoke()
    {
        $sort_init = 10;
        $last_contact = Partner::orderBy('sort','desc')
                ->limit(1)
                ->first();
        if (!empty($last_contact->sort)) {
            $sort_init = $last_contact->sort + 10;
        }
        return view('manager.partner.create', compact('sort_init'));
    }
}
