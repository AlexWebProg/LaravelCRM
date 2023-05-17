<?php

namespace App\Http\Controllers\Manager\Partner;

use App\Http\Controllers\Controller;
use App\Models\Partner;

class IndexController extends Controller
{
    public function __invoke()
    {
        if (empty(auth()->user()->is_admin)) {
            // Для сотрудника - список, аналогичный списку у заказчика
            $partners = Partner::where('is_active',1)
                ->orderBy('sort','asc')
                ->get();
            return view('manager.partner.index_not_admin', compact('partners'));
        } else {
            // Для руководителя - таблица пратнёров с возможностью редактирования
            $partners = Partner::all();
            return view('manager.partner.index', compact('partners'));
        }

    }
}
