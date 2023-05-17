<?php

namespace App\Http\Controllers\Manager\Quiz\Template;

use App\Http\Controllers\Manager\Quiz\BaseController;
use App\Models\QuizSurvey;
use App\Models\QuizTemplate;

class EditController extends BaseController
{
    public function __invoke(QuizTemplate $template)
    {
        return view('manager.quiz.template.form', compact('template'));
    }
}
