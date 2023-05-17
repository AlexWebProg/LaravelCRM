<?php

namespace App\Http\Controllers\Manager\Quiz\Template;

use App\Models\QuizTemplate;
use App\Http\Controllers\Manager\Quiz\BaseController;

class IndexController extends BaseController
{
    public function __invoke()
    {
        $templates = QuizTemplate::all();

        return view('manager.quiz.template.index', compact('templates'));
    }
}
