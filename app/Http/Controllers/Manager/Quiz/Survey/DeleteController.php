<?php

namespace App\Http\Controllers\Manager\Quiz\Survey;

use App\Http\Controllers\Manager\Quiz\BaseController;
use App\Models\Chat;
use App\Models\QuizAnswer;
use App\Models\QuizSurvey;
use App\Models\QuizSurveyClient;
use App\Models\User;

class DeleteController extends BaseController
{
    public function __invoke(QuizSurvey $survey)
    {
        // Удаляем сообщения с опросом в чатах
        $arMessages = Chat::where('quiz_survey_id', $survey->id)->get();
        if (!empty($arMessages) && count($arMessages)) {
            foreach ($arMessages as $message) {
                User::where('id', $message->client_id)->update(['chat_updated_at' => now()]);
                $message->text = 'Опрос удалён';
                $message->quiz_survey_id = null;
                $message->quiz_survey_completed_at = null;
                $message->deleted_at = now();
                $message->save();
            }
        }

        // Удаляем участников опроса
        QuizSurveyClient::where('survey_id', $survey->id)->delete();

        // Удаляем ответы на опрос
        QuizAnswer::where('survey_id', $survey->id)->delete();

        // Удаляем сам опрос
        $survey->delete();

        return redirect()
            ->route('manager.quiz.survey.index')
            ->with('notification',[
                'class' => 'success',
                'message' => 'Опрос удалён'
            ]);
    }
}
