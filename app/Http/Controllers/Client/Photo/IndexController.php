<?php

namespace App\Http\Controllers\Client\Photo;

use App\Http\Controllers\Controller;
use App\Models\Stat;

class IndexController extends Controller
{
    public function __invoke()
    {
        Stat::create(['user_id' => auth()->user()->id, 'action' => 'Фото']);
        return view('client.photo.index');
    }
}
