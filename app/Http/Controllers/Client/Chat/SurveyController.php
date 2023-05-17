<?php

namespace App\Http\Controllers\Client\Chat;

use App\Http\Requests\Client\Chat\SurveyRequest;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\QuizAnswer;
use App\Models\QuizSurvey;
use App\Models\QuizSurveyClient;
use App\Models\Stat;
use App\Models\User;

class SurveyController extends Controller
{
    public function __invoke(SurveyRequest $request)
    {
        $data = $request->validated();

        // Если опрос не удалён
        if (!empty(QuizSurvey::find($data['survey_id']))) {
            // Указываем, что участник опроса заполнил опрос
            QuizSurveyClient::where('survey_id',$data['survey_id'])->where('client_id',auth()->user()->id)->update(['completed_at' => now()]);
            // Указываем в сообщении, что опрос заполнен
            Chat::where('id', $data['message_id'])->update(['quiz_survey_completed_at' => now()]);
            // Меняем дату обновления чата
            User::where('id', auth()->user()->id)->update(['chat_updated_at' => now()]);
            // Пишем статистику
            Stat::create(['user_id' => auth()->user()->id, 'action' => 'Ответ на опрос в чате']);
            // Сохраняем ответы на вопросы
            if (!empty($data['answers']) && count($data['answers'])) {
                foreach ($data['answers'] as $arAnswer) {
                    $arAnswer['client_id'] = auth()->user()->id;
                    QuizAnswer::create($arAnswer);
                }
            }
        }

        // Возвращаемся к чату
        return redirect()
            ->route('client.chat')
            ->with('notification', [
                'class' => 'success',
                'message' => 'Спасибо за участие в опросе!'
            ]);
    }
}
