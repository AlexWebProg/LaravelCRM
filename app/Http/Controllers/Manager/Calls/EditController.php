<?php

namespace App\Http\Controllers\Manager\Calls;

use App\Models\Calls;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class EditController extends BaseController
{
    public function __invoke(string $date)
    {
        $validator = Validator::make(['date' => $date], [
            'date' => [
                'required', 'date_format:Y-m-d'
            ]
        ]);
        if ($validator->fails()) {
            return redirect()->route('manager.calls.month');
        }
        $call = Calls::where('date',$date)->first();
        $dt = Carbon::parse($date);
        $arDates = [
            'dbDate' => $date,
            'strDate' => $dt->isoFormat('D MMMM YYYY'),
            'intMonth' => date('m.Y',strtotime($date)),
            'strMonthYear' => $dt->isoFormat('MMMM YYYY'),
        ];
        return view('manager.calls.edit', compact('call', 'arDates'));
    }
}
