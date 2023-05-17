<?php

namespace App\Http\Controllers\Manager\Quiz\Survey;

use App\Models\QuizSurvey;
use App\Http\Controllers\Manager\Quiz\BaseController;

class ShowController extends BaseController
{
    public function __invoke(QuizSurvey $survey)
    {
        // Считаем ответы на каждый вопрос
        $arAnswers = [];
        if (!empty($survey->template->questions) && count($survey->template->questions)) {
            foreach ($survey->template->questions as $question) {
                $arAnswers[$question->id] = [
                    'answers' => $survey->answers->where('question_id',$question->id)->sortByDesc(function ($item) {
                        return strtotime($item->created_at);
                    })
                ];
                if ($question->rating_enabled) {
                    $arAnswers[$question->id]['arAnswerRating'] = [];
                    $arAnswers[$question->id]['totalAnswersWithRating'] = 0;
                    $intSumRating = 0;
                    $arAnswers[$question->id]['averageRating'] = 0;
                    for ($rating = $question->rating_to; $rating >= $question->rating_from; $rating--) {
                        $arAnswers[$question->id]['rating_label'][$rating] = '';
                        for ($star = 1; $star <= $rating; $star++) {
                            $arAnswers[$question->id]['rating_label'][$rating] .= "\u2605 ";
                        }
                        if ($question->rating_to > $rating) {
                            for ($star = $rating + 1; $star <= $question->rating_to; $star++) {
                                $arAnswers[$question->id]['rating_label'][$rating] .= "\u2606 ";
                            }
                        }
                        $arAnswers[$question->id]['arAnswerRating'][$rating] = $survey->answers->where('question_id',$question->id)->where('rating',$rating)->count();
                        $arAnswers[$question->id]['totalAnswersWithRating'] = $arAnswers[$question->id]['totalAnswersWithRating'] + $arAnswers[$question->id]['arAnswerRating'][$rating];
                        $intSumRating = $intSumRating + $rating * $arAnswers[$question->id]['arAnswerRating'][$rating];
                    }
                    // Средняя оценка
                    if (!empty($arAnswers[$question->id]['totalAnswersWithRating'])) {
                        $arAnswers[$question->id]['averageRating'] = number_format($intSumRating/$arAnswers[$question->id]['totalAnswersWithRating'], 2, '.', ' ');
                    }
                    unset($intSumRating);
                }
                if ($question->comment_enabled) {
                    $arAnswers[$question->id]['totalComments'] = $survey->answers->where('question_id',$question->id)->whereNotNull('comment')->count();
                }
            }
        }
        return view('manager.quiz.survey.show',compact('survey','arAnswers'));
    }
}
