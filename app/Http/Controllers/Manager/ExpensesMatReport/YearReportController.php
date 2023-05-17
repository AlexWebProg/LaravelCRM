<?php

namespace App\Http\Controllers\Manager\ExpensesMatReport;

class YearReportController extends BaseController
{
    public function __invoke(int $year)
    {
        $year_report = $this->service->getYearReport($year);
        return view('manager.expenses_mat_report.year_report', compact('year_report', 'year'));
    }
}
