<?php

namespace App\Http\Controllers\Manager\Partner;

use App\Http\Controllers\Controller;
use App\Models\Partner;

class EditController extends Controller
{
    public function __invoke(Partner $partner)
    {
        return view('manager.partner.edit', compact('partner'));
    }
}
