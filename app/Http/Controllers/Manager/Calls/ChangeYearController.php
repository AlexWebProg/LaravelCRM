<?php

namespace App\Http\Controllers\Manager\Calls;
use Illuminate\Http\Request;

class ChangeYearController extends BaseController
{
    public function __invoke(Request $request)
    {
        $year = (int) $request->input('input_year');
        return redirect()
            ->route('manager.calls.year', $year);
    }
}
