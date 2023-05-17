<?php

namespace App\Http\Controllers\Manager\Partner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Partner\UpdateRequest;
use App\Models\Partner;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Partner $partner)
    {
        $data = $request->validated();
        $partner->update($data);
        return redirect()
            ->route('manager.partner.edit', $partner->id)
            ->with('notification',[
                'class' => 'success',
                'message' => 'Данные успешно обновлены'
            ]);
    }
}
