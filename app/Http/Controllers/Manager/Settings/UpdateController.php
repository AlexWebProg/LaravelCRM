<?php

namespace App\Http\Controllers\Manager\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Settings\UpdateRequest;
use App\Models\Settings;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request)
    {
        $data = $request->validated();
        foreach ($data as $name => $value) {
            Settings::where('name', $name)->update(['value' => $value]);
        }
        return redirect()
            ->route('manager.settings.edit')
            ->with('notification',[
                'class' => 'success',
                'message' => 'Данные успешно обновлены'
            ]);
    }
}
