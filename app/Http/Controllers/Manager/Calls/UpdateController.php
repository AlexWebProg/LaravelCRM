<?php

namespace App\Http\Controllers\Manager\Calls;

use App\Http\Requests\Manager\Calls\UpdateRequest;
use App\Models\Calls;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request)
    {
        $data = $request->validated();
        if (is_null($data['repair_full'])) $data['repair_full'] = 0;
        if (is_null($data['repair_partial'])) $data['repair_partial'] = 0;
        if (is_null($data['advertising'])) $data['advertising'] = 0;
        if (is_null($data['evening_calls'])) $data['evening_calls'] = 0;
        if (is_null($data['day_total'])) $data['day_total'] = 0;
        if (is_null($data['signed_up'])) $data['signed_up'] = 0;
        if (is_null($data['est_wo_dep'])) $data['est_wo_dep'] = 0;
        if (is_null($data['from_youtube'])) $data['from_youtube'] = 0;
        if (is_null($data['from_dzen'])) $data['from_dzen'] = 0;
        if (is_null($data['from_rutube'])) $data['from_rutube'] = 0;
        if (is_null($data['from_telegram'])) $data['from_telegram'] = 0;
        if (is_null($data['from_tiktok'])) $data['from_tiktok'] = 0;
        if (is_null($data['from_vk'])) $data['from_vk'] = 0;
        if (is_null($data['from_site'])) $data['from_site'] = 0;
        if (is_null($data['from_people'])) $data['from_people'] = 0;
        if (is_null($data['from_other'])) $data['from_other'] = 0;

        if (is_null($data['manager_id'])) $data['manager_id'] = auth()->user()->id;

        $call = Calls::where('date',$data['date'])->firstOrCreate();
        $call->update($data);

        return redirect()
            ->route('manager.calls.month', date('m.Y',strtotime($data['date'])))
            ->with('notification',[
                'class' => 'success',
                'message' => 'Данные успешно обновлены'
            ]);
    }
}
