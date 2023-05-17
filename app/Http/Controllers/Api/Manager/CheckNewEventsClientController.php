<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Chat;
use App\Models\Estimate;
use App\Models\MasterEstimate;
use App\Models\EstimateComment;
use App\Models\MasterEstimateComment;
use App\Models\Photo;
use App\Models\Task;
use App\Models\TechDoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

// Новые события для одного объекта
class CheckNewEventsClientController extends Controller
{
    public function __invoke(Request $request)
    {
        $client_id = (int) $request->input('client_id');
        $manager_id = (int) $request->input('manager_id');
        $currentUser = User::find($manager_id);
        $arData = [];
        $arData['intNewChatMessages'] = Gate::forUser($currentUser)->allows('show_chat') ? Chat::where('client_id', $client_id)->whereNull('viewed->'.$manager_id)->count() : 0;
        $arData['intNewPhoto'] = Gate::forUser($currentUser)->allows('show_photo') ? Photo::where('client_id', $client_id)->whereNull('viewed->'.$manager_id)->count() :0;
        $arData['intNewEstimate'] = Gate::forUser($currentUser)->allows('show_estimate') ? Estimate::where('client_id', $client_id)->whereNull('viewed->'.$manager_id)->count() + EstimateComment::where('client_id', $client_id)->whereNull('viewed->'.$manager_id)->count() : 0;
        $arData['intNewMasterEstimate'] = Gate::forUser($currentUser)->allows('show_master_estimate') ? MasterEstimate::where('client_id', $client_id)->whereNull('viewed->'.$manager_id)->count() + MasterEstimateComment::where('client_id', $client_id)->whereNull('viewed->'.$manager_id)->count() : 0;
        $arData['intNewTechDoc'] = Gate::forUser($currentUser)->allows('show_tech_doc') ? TechDoc::where('client_id', $client_id)->whereNull('viewed->'.$manager_id)->count() : 0;
        $arData['intNewActiveTasks'] = Gate::forUser($currentUser)->allows('show_plan') ? Task::where('client_id', $client_id)->whereNull('closed_at')->whereNull('viewed->'.$manager_id)->count() : 0;
        return response($arData);
    }
}
