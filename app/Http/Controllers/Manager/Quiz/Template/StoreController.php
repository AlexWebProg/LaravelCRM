<?php

namespace App\Http\Controllers\Manager\Quiz\Template;

use App\Models\QuizTemplate;
use App\Models\QuizQuestion;
use App\Http\Controllers\Manager\Quiz\BaseController;
use App\Http\Requests\Manager\Quiz\Template\StoreRequest;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        // Массив вопросов
        if (!empty($data['question'])) {
            $arQuestions = $data['question'];
            unset($data['question']);
        }

        // Работа с шаблоном
        if (is_null($data['id'])) {
            // Создание шаблона
            unset($data['id']);
            $data['manager_id'] = auth()->user()->id;
            $template = QuizTemplate::create($data);
            if (!empty($arQuestions) && count($arQuestions)) {
                foreach ($arQuestions as $k => $arQuestion) {
                    $arQuestions[$k]['template_id'] = $template->id;
                }
            }
        } else {
            // Обновление шаблона
            $template = QuizTemplate::find($data['id']);
            // Если шаблон используется в опросах, обновить его нельзя
            if (!empty($template?->surveys->count())) {
                return redirect()
                    ->route('manager.quiz.template.edit', $template->id)
                    ->with('notification', [
                        'class' => 'danger',
                        'message' => 'Этот шаблон нельзя отредактировать, так как он используется в уже запущенных опросах.'
                    ]);
            }
            unset($data['id']);
            $template->update($data);
        }

        // Разбор вопросов
        $arQuestionIDs = [];
        if (!empty($arQuestions) && count($arQuestions)) {
            foreach ($arQuestions as $arQuestion) {
                if (empty($arQuestion['id'])) {
                    // Создание новых вопросов
                    unset($arQuestion['id']);
                    $question = QuizQuestion::create($arQuestion);
                } else {
                    // Обновление существующих
                    $question = QuizQuestion::find($arQuestion['id']);
                    unset($arQuestion['id']);
                    $question->update($arQuestion);
                }
                $arQuestionIDs[] = $question->id;
                unset($question);
            }
        }
        // Удаление старых
        QuizQuestion::where('template_id',$template->id)
            ->whereNotIn('id',$arQuestionIDs)
            ->delete();

        return redirect()
            ->route('manager.quiz.template.edit', $template->id)
            ->with('notification', [
                'class' => 'success',
                'message' => 'Шаблон опроса сохранён'
            ]);

    }
}
