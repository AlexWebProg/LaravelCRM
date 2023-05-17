<?php

namespace App\Http\Controllers\Manager\Map;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class IndexController extends Controller
{
    public function __invoke($is_active = 1)
    {
        $clients = User::where('is_active', 1)
        ->where('type', 0)
        ->whereNotNull('coordinates')
        ->when(!Gate::allows('show_ob_status_2'), function ($query) {
            return $query->where('ob_status', '!=', 2);
        })
        ->orderBy('created_at','DESC')
        ->get();

        if (!empty($clients) && count($clients)) {
            foreach ($clients as &$client) {
                if ($client->ob_status === 2) {
                    $client->in_process_map_place_mark = '<i class="fa fa-wrench mr-2" aria-hidden="true"></i>На гарантии';
                    $client->place_mark_preset = 'islands#darkGreenDotIcon';
                } elseif (empty($client->in_process)) {
                    $client->in_process_map_place_mark = '<i class="fa fa-ban mr-2" aria-hidden="true"></i>Работа ещё не начата';
                    $client->place_mark_preset = 'islands#greyDotIcon';
                } else {
                    $client->in_process_map_place_mark = '<i class="fa fa-check mr-2" aria-hidden="true"></i>Работа начата';
                    $client->place_mark_preset = 'islands#darkblueDotIcon';
                }
            }
        }

        return view('manager.map.index', compact('clients'));
    }
}
