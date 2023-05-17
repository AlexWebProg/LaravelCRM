<?php

namespace App\Http\Controllers\Manager\Client;

use App\Models\User;
use App\Models\Estimate;

class EstimateDeleteController extends BaseController
{
    public function __invoke(User $client, Estimate $estimate)
    {
        if (empty($estimate) || empty($client)) {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'Документ или объект не найден'
                ]);
        }

        if (!empty($estimate->src)) {
            @unlink(storage_path('app/public/'.$estimate->src));
        }
        $estimate->delete();
        return redirect()
            ->back()
            ->with('notification', [
                'class' => 'success',
                'message' => 'Смета удалена'
            ]);
    }
}
