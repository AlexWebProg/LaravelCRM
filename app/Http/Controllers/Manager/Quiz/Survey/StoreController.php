<?php

namespace App\Http\Controllers\Manager\Quiz\Survey;

use App\Http\Controllers\Manager\Quiz\BaseController;
use App\Http\Requests\Manager\Quiz\Survey\StoreRequest;
use App\Models\Chat;
use App\Models\QuizSurvey;
use App\Models\QuizSurveyClient;
use App\Models\User;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        // Создаём опрос
        $data['manager_id'] = auth()->user()->id;
        $arClients = $data['client'];
        unset($data['client']);
        if (!empty($data['all_clients'])) unset($data['all_clients']);
        if (!empty($data['to_in_process_1'])) unset($data['to_in_process_1']);
        if (!empty($data['to_in_process_0'])) unset($data['to_in_process_0']);
        if (!empty($data['to_ob_status_2'])) unset($data['to_ob_status_2']);
        $survey = QuizSurvey::create($data);

        // Рассылаем опрос выбранным объектам
        if (!empty($survey->id) && !empty($arClients) && count($arClients)) {
            $message = [
                'manager_id' => auth()->user()->id,
                'quiz_survey_id' => $survey->id
            ];
            // Сразу же отметим сообщение прочитанным всеми сотрудниками
            $arViewedUsers = [];
            $dateViewed = date('d.m.Y H:i',time());
            $managers = User::where('is_active', 1)
                ->where('type', 1)
                ->orderBy('name')
                ->get();
            if (!empty($managers) && count($managers)) {
                foreach ($managers as $manager) {
                    $arViewedUsers[$manager->id] = [
                        'name' => $manager->name,
                        'date' => $dateViewed,
                    ];
                }
            }
            $message['viewed'] = $arViewedUsers;
            unset($dateViewed,$arViewedUsers);

            // Заполняем список участников опроса
            foreach ($arClients as $intClientId) {
                QuizSurveyClient::create([
                    'survey_id' => $survey->id,
                    'client_id' => $intClientId
                ]);
            }

            // Отправляем опрос в чаты
            foreach ($arClients as $intClientId) {
                $message['client_id'] = $intClientId;
                $message_created = Chat::create($message);
                if (!empty($message_created->id)) {
                    User::where('id', $intClientId)->update(['chat_updated_at' => now()]);
                }
                unset($message_created);
            }
        }

        return redirect()
            ->route('manager.quiz.survey.index')
            ->with('notification', [
                'class' => 'success',
                'message' => 'Опрос отправлен в чаты выбранных заказчиков'
            ]);

    }
}
