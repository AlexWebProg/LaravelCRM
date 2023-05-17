<?php

namespace App\Http\Controllers\Manager\ExpensesMatReport;
use Illuminate\Http\Request;

class YearController extends BaseController
{
    public function __invoke(Request $request)
    {
        $year = (int) $request->input('input_year');
        return redirect()
            ->route('manager.expenses_mat_report.year_report', $year);
    }
}
