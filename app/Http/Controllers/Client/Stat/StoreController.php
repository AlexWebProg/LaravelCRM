<?php

namespace App\Http\Controllers\Client\Stat;

use App\Models\Stat;
use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    public function __invoke($action)
    {
        $arActions = [
            'webcam' => 'Веб-камера',
            'pay' => 'Оплата',
            'logout' => 'Выход',
        ];
        if (!empty($arActions[$action])) {
            Stat::create(['user_id' => auth()->user()->id, 'action' => $arActions[$action]]);
        }
    }
}
