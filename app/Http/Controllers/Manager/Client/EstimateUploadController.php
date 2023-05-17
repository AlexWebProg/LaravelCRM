<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Requests\Manager\Client\EstimateUploadRequest;
use App\Models\Estimate;
use App\Models\User;
use App\Notifications\MailEstimateUploaded;
use Illuminate\Support\Facades\Storage;

class EstimateUploadController extends BaseController
{
    public function __invoke(EstimateUploadRequest $request, User $client)
    {
        $data = $request->validated();
        // Загружаем файл
        $path = Storage::disk('public')->put('/estimate', $data['file']);
        if (!empty($client->id) && !empty($path)) {
            // Сохраняем смету в БД
            $estimate = Estimate::create([
                'client_id' => $client->id,
                'src' => $path,
                'uploaded_manager_id' => auth()->user()->id
            ]);
            // Если объект активен, оповещаем заказчика о загрузке сметы
            if (!empty($client->is_active) && !empty($estimate) && !empty($estimate->id)) {
                $client->notify(new MailEstimateUploaded($client->name));
            }
        }
        if (!empty($estimate) && !empty($estimate->id)) {
            $request->session()->flash('notification', [
                'class' => 'success',
                'message' => 'Файл успешно загружен'
            ]);
            return response(['message'=> 'Файл успешно загружен'],200);
        }
        return response(['message'=> 'Ошибка'],500);
    }
}
