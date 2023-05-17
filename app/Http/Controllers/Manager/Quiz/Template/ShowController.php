<?php

namespace App\Http\Controllers\Manager\Quiz\Template;

use App\Models\QuizTemplate;
use App\Models\QuizAnswer;
use App\Http\Controllers\Manager\Quiz\BaseController;

class ShowController extends BaseController
{
    public function __invoke(QuizTemplate $template)
    {
        // Считаем ответы на каждый вопрос
        $arAnswers = [];
        if (!empty($template->questions) && count($template->questions)) {
            foreach ($template->questions as $question) {
                $arAnswers[$question->id] = [
                    'answers' => QuizAnswer::where('question_id',$question->id)->orderBy('created_at','DESC')->get()
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
                        $arAnswers[$question->id]['arAnswerRating'][$rating] = $arAnswers[$question->id]['answers']->where('rating',$rating)->count();
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
                    $arAnswers[$question->id]['totalComments'] = $arAnswers[$question->id]['answers']->whereNotNull('comment')->count();
                }
            }
        }
        //dd($template->clients);
        return view('manager.quiz.template.show',compact('template','arAnswers'));
    }
}
