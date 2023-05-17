<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Estimate;
use App\Models\EstimateComment;
use App\Models\Photo;
use App\Models\TechDoc;
use App\Models\User;
use Illuminate\Http\Request;

// Новые события для одного объекта
class CheckNewEventsController extends Controller
{
    public function __invoke(Request $request)
    {
        $client_id = (int) $request->input('id');
        $arData = [];
        $arData['intNewChatMessages'] = Chat::where('client_id', $client_id)->whereNotNull('manager_id')->whereNull('viewed->'.$client_id)->count();
        $arData['intNewPhoto'] = Photo::where('client_id', $client_id)->whereNull('viewed->'.$client_id)->count();
        $arData['intNewEstimate'] = Estimate::where('client_id', $client_id)->whereNull('viewed->'.$client_id)->count() + EstimateComment::where('client_id', $client_id)->whereNull('viewed->'.$client_id)->count();
        $arData['intNewTechDoc'] = TechDoc::where('client_id', $client_id)->whereNull('viewed->'.$client_id)->count();
        $arData['intPlanUpdated'] = User::where('id', $client_id)->first()->plan_updated;
        return response($arData);
    }
}
