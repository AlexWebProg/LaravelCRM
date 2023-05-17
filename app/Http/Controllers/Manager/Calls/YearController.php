<?php

namespace App\Http\Controllers\Manager\Calls;

class YearController extends BaseController
{
    public function __invoke(int $year)
    {
        $year_report = $this->service->getYear($year);
        return view('manager.calls.year', compact('year_report', 'year'));
    }
}
