<?php

namespace App\Http\Controllers\Client\TechDoc;

use App\Http\Controllers\Controller;
use App\Models\Stat;
use App\Models\TechDoc;
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
        Stat::create(['user_id' => auth()->user()->id, 'action' => 'Техническая документация']);
        $tech_docs = TechDoc::where('client_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->get();

        // Просмотр пользователями
        if (count($tech_docs)) {
            foreach ($tech_docs as $tech_doc) {
                $this->service->makeItemViewed($tech_doc);
            }
        }

        return view('client.tech_doc.index',compact('tech_docs'));
    }
}
