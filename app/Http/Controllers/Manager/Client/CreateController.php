<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;

class CreateController extends Controller
{
    public function __invoke()
    {
        return view('manager.client.create');
    }
}
