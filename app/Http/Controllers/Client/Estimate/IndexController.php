<?php

namespace App\Http\Controllers\Client\Estimate;

use App\Http\Controllers\Controller;
use App\Models\Stat;
use App\Services\Common\ViewItemService;

class IndexController extends Controller
{
    public object $service;

    public function __construct(ViewItemService $service)
    {
        $this->service = $service;
    }

    public function __invoke()
    {
        Stat::create(['user_id' => auth()->user()->id, 'action' => 'Смета']);

        // Просмотр пользователями
        if (!empty(auth()->user()->estimate_comment)) {
            $this->service->makeItemViewed(auth()->user()->estimate_comment);
        }
        if (!empty(auth()->user()->estimate) && count(auth()->user()->estimate)) {
            foreach (auth()->user()->estimate as $estimate) {
                $this->service->makeItemViewed($estimate);
            }
        }

        return view('client.estimate.index');
    }
}
