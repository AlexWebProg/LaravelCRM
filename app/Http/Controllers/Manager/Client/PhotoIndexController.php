<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\User;
use App\Services\Common\ViewItemService;

class PhotoIndexController extends Controller
{
    public object $service;

    public function __construct(ViewItemService $service)
    {
        $this->service = $service;
    }

    public function __invoke(User $client)
    {
        $photos = Photo::where('client_id', $client->id)
            ->orderBy('id', 'desc')
            ->get();

        // Просмотр пользователями
        if (count($photos)) {
            foreach ($photos as $photo) {
                $this->service->makeItemViewed($photo);
            }
        }

        return view('manager.client.edit.photo_list', compact('photos'));
    }
}
