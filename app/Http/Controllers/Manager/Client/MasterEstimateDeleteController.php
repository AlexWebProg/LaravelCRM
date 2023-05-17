<?php

namespace App\Http\Controllers\Manager\Client;

use App\Models\User;
use App\Models\MasterEstimate;

class MasterEstimateDeleteController extends BaseController
{
    public function __invoke(User $client, MasterEstimate $master_estimate)
    {
        if (empty($master_estimate) || empty($client)) {
            return redirect()
                ->back()
                ->with('notification', [
                    'class' => 'danger',
                    'message' => 'Документ или объект не найден'
                ]);
        }

        if (!empty($master_estimate->src)) {
            @unlink(storage_path('app/public/'.$master_estimate->src));
        }
        $master_estimate->delete();
        return redirect()
            ->back()
            ->with('notification', [
                'class' => 'success',
                'message' => 'Смета удалена'
            ]);
    }
}
