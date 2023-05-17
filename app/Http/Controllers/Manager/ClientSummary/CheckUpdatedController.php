<?php

namespace App\Http\Controllers\Manager\ClientSummary;

use App\Models\User;
use App\Http\Requests\Manager\ClientSummary\CheckUpdatedRequest;

class CheckUpdatedController extends BaseController
{
    public function __invoke(CheckUpdatedRequest $request)
    {
        $data = $request->validated();
        $updated_clients = User::where('summary_updated_at', '>', $data['updated_at'])->get();
        $arResponse = ['clients' => [], 'date' => date('Y-m-d H:i:s')];
        if (count($updated_clients)) {
            foreach ($updated_clients as $client) {
                $arResponse['clients'][$client->id] = [
                    'summary_address' => $client->summary_address,
                    'plan_dates' => $client->plan_dates,
                    'summary_master' => $client->summary_master,
                    'summary_webcam' => $client->summary_webcam,
                    'plan_questions' => $client->plan_questions,
                    'plan_check' => $client->plan_check,
                    'summary_pay' => $client->summary_pay,
                    'summary_delivery' => $client->summary_delivery,
                    'in_process' => $client->in_process
                ];
            }
        }
        return response($arResponse,200);
    }
}
