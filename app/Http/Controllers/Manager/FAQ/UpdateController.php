<?php

namespace App\Http\Controllers\Manager\FAQ;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\FAQ\UpdateRequest;
use App\Models\FAQ;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, FAQ $faq)
    {
        $data = $request->validated();
        $faq->update($data);
        return redirect()
            ->route('manager.faq.edit', $faq->id)
            ->with('notification',[
                'class' => 'success',
                'message' => 'Данные успешно обновлены'
            ]);
    }
}
