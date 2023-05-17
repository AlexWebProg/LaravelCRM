<?php

namespace App\Http\Controllers\Manager\Quiz\Survey;

use App\Models\QuizTemplate;
use App\Http\Controllers\Manager\Quiz\BaseController;
use App\Models\User;

class CreateController extends BaseController
{
    public function __invoke($template_id = null)
    {
        $templates = QuizTemplate::orderBy('name')->get();
        $clients = User::where('is_active', 1)
            ->where('type', 0)
            ->where('id', '!=', config('global.intDemoObject')) // Демо-объект
            ->orderBy('address')
            ->get();
        return view('manager.quiz.survey.create',compact('templates','clients', 'template_id'));
    }
}
