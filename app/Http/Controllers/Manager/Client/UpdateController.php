<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Client\UpdateRequest;
use App\Models\User;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, User $client)
    {
        $data = $request->validated();
        if ($data['in_process'] !== $client->in_process) $data['summary_updated_at'] = date('Y-m-d H:i:s');
        if (!empty($data['ob_status'])) {
            if ($data['ob_status'] === '3') {
                $data['is_active'] = 0;
                $data['chat_remember'] = null;
            } else {
                $data['is_active'] = 1;
            }
            if ($data['ob_status'] !== '2' && !empty($data['warranty_end'])) {
                unset($data['warranty_end']);
            } elseif (!empty($data['warranty_end'])) {
                $data['warranty_end'] = date('Y-m-d',strtotime($data['warranty_end']));
            }
        }

        $client->update($data);
        return redirect()
            ->route('manager.client.edit', $client->id)
            ->with('notification',[
                'class' => 'success',
                'message' => 'Данные успешно обновлены'
            ]);
    }
}
