<?php

namespace App\Http\Controllers\Manager\Calls;

class MonthController extends BaseController
{
    public function __invoke(string $month_year = '')
    {
        if (empty($month_year)) $month_year = date('m.Y');
        $month = $this->service->getMonth($month_year);
        return view('manager.calls.month', compact('month'));
    }
}
