<?php

namespace App\Http\Controllers\Manager\Quiz\Template;

use App\Http\Controllers\Manager\Quiz\BaseController;
use App\Models\QuizTemplate;

class DeleteController extends BaseController
{
    public function __invoke(QuizTemplate $template)
    {
        if (!empty($template->questions) && count($template->questions)) {
            foreach ($template->questions as $question) {
                $question->delete();
            }
        }
        $template->delete();
        return redirect()
            ->route('manager.quiz.template.index')
            ->with('notification',[
                'class' => 'success',
                'message' => 'Шаблон удалён'
            ]);
    }
}
