<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Client\PhotoUpdateRequest;
use App\Models\Photo;
use App\Models\User;

class PhotoUpdateController extends Controller
{
    public function __invoke(PhotoUpdateRequest $request, User $client)
    {
        $data = $request->validated();

        $photo = Photo::find($data['id']);
        $photo->comment = empty($data['comment']) ? null : $data['comment'];
        $photo->save();

        return redirect()
            ->route('manager.client.edit', [$client->id,'photo'])
            ->with('notification',[
                'class' => 'success',
                'message' => 'Данные успешно обновлены'
            ]);
    }
}
