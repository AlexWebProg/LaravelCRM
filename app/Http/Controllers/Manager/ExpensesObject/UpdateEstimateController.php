<?php

namespace App\Http\Controllers\Manager\ExpensesObject;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\ExpensesObject\UpdateEstimateRequest;
use App\Models\User;

class UpdateEstimateController extends Controller
{
    public function __invoke(UpdateEstimateRequest $request, User $client)
    {
        $data = $request->validated();
        $client->update($data);
        return redirect()
            ->route('manager.client.edit', [$client->id,'expenses'])
            ->with('notification', [
                'class' => 'success',
                'message' => 'Данные обновлены'
            ]);
    }
}
