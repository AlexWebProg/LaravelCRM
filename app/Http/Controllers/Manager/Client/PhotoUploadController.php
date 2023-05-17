<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Client\PhotoUploadRequest;
use App\Models\Photo;
use App\Models\User;
use App\Notifications\MailPhotoUploaded;
use App\Services\Common\UploadFileService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PhotoUploadController extends Controller
{
    public object $service;

    public function __construct(UploadFileService $service)
    {
        $this->service = $service;
    }

    public function __invoke(PhotoUploadRequest $request, User $client)
    {
        $data = $request->validated();
        // Видео в mov сохраняем в mp4, остальные файлы - как есть
        $data['src'] = $this->service->saveFile($data['file'],'photo');
        $data['client_id'] = $client->id;
        $data['uploaded_manager_id'] = auth()->user()->id;
        if (!empty($data['file']) && !empty($data['client_id'])) {
            unset($data['file']);
            // Получаем дату последнего загруженного для объекта файла. Если раньше 5 минут назад, отправляем письмо
            if (!Photo::where('client_id',$data['client_id'])
                ->where('created_at', '>', Carbon::now()->subMinutes(5)->toDateTimeString())
                ->exists() && !empty($client->is_active)) {
                    $client->notify(new MailPhotoUploaded($client->name));
            }
            // Сохраняем фото в БД
            $photo = Photo::create($data);
        }
        if (!empty($photo->id)) {
            return response(['message'=> 'Фото успешно загружено'],200);
        } elseif (!empty($data['src'])) {
            Storage::delete($data['src']);
        }
        return response(['message'=> 'Ошибка'],500);
    }
}
