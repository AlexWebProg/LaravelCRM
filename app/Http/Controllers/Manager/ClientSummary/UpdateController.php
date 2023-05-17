<?php

namespace App\Http\Controllers\Manager\ClientSummary;

use App\Http\Requests\Manager\ClientSummary\UpdateRequest;
use App\Models\User;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request )
    {
        $data = $request->validated();
        $client = User::find($data['client']);
        if (!empty($client) && array_key_exists($data['name'],$client->getAttributes())) {
            $client->{$data['name']} = $data['value'];
            $client->save();
            if ($client->wasChanged()) {
                if (in_array($data['name'],['plan_dates','plan_questions','plan_check'])) {
                    $client->plan_updated = 1;
                }
                $client->summary_updated_at = date('Y-m-d H:i:s');
                $client->save();
                return response(['updated' => 1],200);
            } else {
                return response(['updated' => 0],200);
            }
        }
        return response([],500);
    }
}
