<?php

namespace App\Http\Controllers\Manager\Main;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __invoke()
    {
        return redirect()->route('manager.client.index',1);
    }
}
