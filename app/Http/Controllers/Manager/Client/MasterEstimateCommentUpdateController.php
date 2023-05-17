<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Client\MasterEstimateCommentUpdateRequest;
use App\Models\User;
use App\Models\MasterEstimateComment;

class MasterEstimateCommentUpdateController extends Controller
{
    public function __invoke(MasterEstimateCommentUpdateRequest $request, User $client)
    {
        $data = $request->validated();
        $master_estimate_comment = MasterEstimateComment::updateOrCreate(
            ['client_id' => $client->id],
            [
                'client_id' => $client->id,
                'manager_id' => auth()->user()->id,
                'text' => $data['master_estimate_comment'],
                'viewed' => [
                    auth()->user()->id => [
                        'name' => auth()->user()->name,
                        'date' => date('d.m.Y H:i'),
                    ]
                ]
            ]
        );
        if (!empty($master_estimate_comment->id)) {
            return redirect()
                ->back()
                ->with('notification',[
                    'class' => 'success',
                    'message' => 'Данные успешно обновлены'
                ]);
        }
        return redirect()
            ->back()
            ->with('notification',[
                'class' => 'danger',
                'message' => 'Данные не были обновлены'
            ]);
    }
}
