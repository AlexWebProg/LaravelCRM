<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Requests\Manager\Client\MasterEstimateUploadRequest;
use App\Models\MasterEstimate;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class MasterEstimateUploadController extends BaseController
{
    public function __invoke(MasterEstimateUploadRequest $request, User $client)
    {
        $data = $request->validated();
        // Загружаем файл
        $path = Storage::disk('public')->put('/master_estimate', $data['file']);
        if (!empty($client->id) && !empty($path)) {
            // Сохраняем смету в БД
            $master_estimate = MasterEstimate::create([
                'client_id' => $client->id,
                'src' => $path,
                'uploaded_manager_id' => auth()->user()->id
            ]);
        }
        if (!empty($master_estimate) && !empty($master_estimate->id)) {
            $request->session()->flash('notification', [
                'class' => 'success',
                'message' => 'Файл успешно загружен'
            ]);
            return response(['message'=> 'Файл успешно загружен'],200);
        }
        return response(['message'=> 'Ошибка'],500);
    }
}
