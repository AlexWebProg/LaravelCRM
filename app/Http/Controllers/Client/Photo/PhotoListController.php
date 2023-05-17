<?php

namespace App\Http\Controllers\Client\Photo;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Services\Common\ViewItemService;

class PhotoListController extends Controller
{
    public object $service;

    public function __construct(ViewItemService $service)
    {
        $this->service = $service;
    }

    public function __invoke()
    {
        $photos = Photo::where('client_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->get();

        // Просмотр пользователями
        if (count($photos)) {
            foreach ($photos as $photo) {
                $this->service->makeItemViewed($photo);
            }
        }

        return view('client.photo.photo_list',compact('photos'));
    }
}
