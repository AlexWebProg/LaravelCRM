<?php

namespace App\Http\Controllers\Manager\Client;

use App\Http\Controllers\Controller;
use App\Models\ChatDraft;
use App\Models\User;
use App\Services\Common\ViewItemService;
use App\Services\Manager\ExpensesObject\ExpensesObjectService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class EditController extends Controller
{
    public object $service;
    public object $expenses_object_service;

    public function __construct(ViewItemService $service, ExpensesObjectService $expenses_object_service)
    {
        $this->service = $service;
        $this->expenses_object_service = $expenses_object_service;
    }

    public function __invoke(User $client, $action_type = 'main_form')
    {
        // Разрешение на просмотр
        if (
            ($action_type === 'webcam' && !Gate::allows('show_webcam'))
            || ($action_type === 'photo' && !Gate::allows('show_photo'))
            || ($action_type === 'plan' && !Gate::allows('show_plan'))
            || ($action_type === 'estimate' && !Gate::allows('show_estimate'))
            || ($action_type === 'master_estimate' && !Gate::allows('show_master_estimate'))
            || ($action_type === 'chat' && !Gate::allows('show_chat'))
            || ($action_type === 'tech_doc' && !Gate::allows('show_tech_doc'))
            ) {
            return redirect()->route('manager.client.edit', $client->id);
        }
        if (
            ($client->ob_status === 2 && !Gate::allows('show_ob_status_2'))
            || ($client->ob_status === 3 && !Gate::allows('show_ob_status_3'))
        ) {
            return redirect()->route('manager.main');
        }

        // Просмотр пользователями разных элементов
        if ($action_type === 'estimate') {
            if (!empty($client->estimate_comment)) {
                $this->service->makeItemViewed($client->estimate_comment);
            }
            if (count($client->estimate)) {
                foreach ($client->estimate as $estimate) {
                    $this->service->makeItemViewed($estimate);
                }
            }
        } elseif ($action_type === 'master_estimate') {
            if (!empty($client->master_estimate_comment)) {
                $this->service->makeItemViewed($client->master_estimate_comment,true);
            }
            if (count($client->master_estimate)) {
                foreach ($client->master_estimate as $master_estimate) {
                    $this->service->makeItemViewed($master_estimate,true);
                }
            }
        } elseif ($action_type === 'tech_doc' && count($client->tech_docs)) {
            foreach ($client->tech_docs as $tech_doc) {
                $this->service->makeItemViewed($tech_doc);
            }
        } elseif ($action_type === 'plan') {
            if (count($client->active_tasks)) {
                foreach ($client->active_tasks as $task) {
                    $this->service->makeItemViewed($task, true);
                }
            }
            if (count($client->closed_tasks)) {
                foreach ($client->closed_tasks as $task) {
                    $this->service->makeItemViewed($task, true);
                }
            }
            $managers = User::where('is_active', 1)
                ->where('type', 1)
                ->orderBy('name')
                ->get();
            return view('manager.client.edit.plan', compact('client', 'managers'));
        } elseif ($action_type === 'chat') {
            // Черновик чата
            $chat_draft = ChatDraft::where(['client_id' => $client->id, 'manager_id' => auth()->user()->id])->first();
            return view('manager.client.edit.chat', compact('client','chat_draft'));
        } elseif ($action_type === 'expenses') {
            if (!Gate::allows('show_expenses_object')) {
                return redirect()->route('manager.client.edit', $client->id);
            }
            $arExpenses = $this->expenses_object_service->getObjectExpenses($client);
            $client_id = $client->id;
            $expenses = $arExpenses['expenses'];
            $expensesTotal = $arExpenses['expensesTotal'];
            $expenses_warnings = $arExpenses['expenses_warnings'];
            return view('manager.client.edit.expenses_index', compact('client','client_id', 'expenses', 'expensesTotal', 'expenses_warnings'));
        }

        // Если не задан срок окончания гарантии, то устанавливаем его через год
        $client->warranty_end_form = empty($client->warranty_end_str) ? Carbon::now()->addYear()->format('d.m.Y') : $client->warranty_end_str;

        return view('manager.client.edit.'.$action_type, compact('client'));
    }
}
