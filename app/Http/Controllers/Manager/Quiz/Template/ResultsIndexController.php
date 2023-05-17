<?php

namespace App\Http\Controllers\Manager\Quiz\Template;

use App\Http\Controllers\Manager\Quiz\BaseController;
use App\Models\QuizTemplate;

class ResultsIndexController extends BaseController
{
    public function __invoke()
    {
        $templates = QuizTemplate::all();
        return view('manager.quiz.template.results_index', compact('templates'));
    }
}
