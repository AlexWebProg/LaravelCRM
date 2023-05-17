<?php

namespace App\Http\Controllers\Manager\Quiz\Template;

use App\Http\Controllers\Manager\Quiz\BaseController;

class CreateController extends BaseController
{
    public function __invoke()
    {
        $template = null;
        return view('manager.quiz.template.form',compact('template'));
    }
}
