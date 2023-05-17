<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

// Новые события для всех объектов в списке
class CheckNewEventsInListController extends Controller
{
    public function __invoke(Request $request)
    {
        $manager_id = (int) $request->input('manager_id');
        $client_ids = (array) $request->input('client_ids');
        $arData = [];

        $arCheckEvents = [];
        $currentUser = User::find($manager_id);
        if (Gate::forUser($currentUser)->allows('show_chat')) $arCheckEvents[] = 'chat';
        if (Gate::forUser($currentUser)->allows('show_photo')) $arCheckEvents[] = 'photo';
        if (Gate::forUser($currentUser)->allows('show_estimate')) {
            $arCheckEvents[] = 'estimate';
            $arCheckEvents[] = 'estimate_comment';
        }
        if (Gate::forUser($currentUser)->allows('show_master_estimate')) {
            $arCheckEvents[] = 'master_estimate';
            $arCheckEvents[] = 'master_estimate_comment';
        }
        if (Gate::forUser($currentUser)->allows('show_tech_doc')) $arCheckEvents[] = 'tech_doc';
        if (Gate::forUser($currentUser)->allows('show_plan')) $arCheckEvents[] = 'task';

        if (count($arCheckEvents)) {
            foreach ($arCheckEvents as $entity) {
                $query = DB::table($entity)
                    ->select('client_id', DB::raw('count(*) as qnt'))
                    ->whereIn('client_id', $client_ids)
                    ->whereNull('viewed->'.$manager_id);
                if ($entity === 'task') $query = $query->whereNull('closed_at');
                $data = $query
                    ->groupBy('client_id')
                    ->get();
                if (!empty($data) && count($data)) {
                    foreach ($data as $item) {
                        if (empty($arData[$item->client_id])) {
                            $arData[$item->client_id] = $item->qnt;
                        } else {
                            $arData[$item->client_id] += $item->qnt;
                        }
                    }
                }
            }
        }

        return response($arData);
    }
}
