<?php

namespace App\Http\Controllers\Manager\ExpensesMatReport;

class IndexController extends BaseController
{
    public function __invoke(string $month_year = '')
    {
        if (empty($month_year)) $month_year = date('m.Y');
        $month_report = $this->service->getMonthReport($month_year);
        return view('manager.expenses_mat_report.index', compact('month_report'));
    }
}
