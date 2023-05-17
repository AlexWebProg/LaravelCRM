<?php

namespace App\Http\Controllers\Manager\Partner;

use App\Http\Controllers\Controller;
use App\Models\Partner;

class DeleteController extends Controller
{
    public function __invoke(Partner $partner)
    {
        // Удаляем партнёра
        $partner->delete();

        return redirect()
            ->route('manager.partner.index')
            ->with('notification',[
                'class' => 'success',
                'message' => 'Партнёр удалён'
            ]);
    }
}
