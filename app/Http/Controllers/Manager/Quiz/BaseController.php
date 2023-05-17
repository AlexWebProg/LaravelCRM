<?php

namespace App\Http\Controllers\Manager\Quiz;

use App\Http\Controllers\Controller;
use App\Services\Manager\Quiz\QuizService;

class BaseController extends Controller
{
    public object $quiz_service;

    public function __construct(QuizService $quiz_service)
    {
        $this->quiz_service = $quiz_service;
    }
}
