<?php

namespace App\Http\Controllers\Manager\Quiz\Survey;

use App\Models\QuizSurvey;
use App\Http\Controllers\Manager\Quiz\BaseController;

class IndexController extends BaseController
{
    public function __invoke()
    {
        $surveys = QuizSurvey::all();
        return view('manager.quiz.survey.index', compact('surveys'));
    }
}
