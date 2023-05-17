<?php

namespace App\Http\Controllers\Manager\Calls;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChangeMonthController extends BaseController
{
    public function __invoke(Request $request)
    {
        $month_year = $request->input('month_year');
        $month_year = Carbon::createFromLocaleIsoFormat('!MMMM YYYY', 'ru', $month_year, null)->format('m.Y');
        return redirect()
            ->route('manager.calls.month', $month_year);
    }
}
