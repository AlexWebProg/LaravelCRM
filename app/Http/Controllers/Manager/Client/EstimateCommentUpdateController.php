<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Client\EstimateCommentUpdateRequest;
use App\Models\User;
use App\Models\EstimateComment;

class EstimateCommentUpdateController extends Controller
{
    public function __invoke(EstimateCommentUpdateRequest $request, User $client)
    {
        $data = $request->validated();
        $estimate_comment = EstimateComment::updateOrCreate(
            ['client_id' => $client->id],
            [
                'client_id' => $client->id,
                'manager_id' => auth()->user()->id,
                'text' => $data['estimate_comment'],
                'viewed' => [
                    auth()->user()->id => [
                        'name' => auth()->user()->name,
                        'date' => date('d.m.Y H:i'),
                    ]
                ]
            ]
        );
        if (!empty($estimate_comment->id)) {
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
