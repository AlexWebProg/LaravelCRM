<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSurvey extends Model
{
    use HasFactory;
    protected $table = 'quiz_survey';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

    // Сотрудник
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id')->withTrashed();
    }

    // Автор
    public function getAuthorAttribute() : string {
        return (!empty($this->manager?->name)) ? $this->manager->name : 'автор не указан';
    }

    // Дата создания в текстовом формате
    public function getCreatedStrAttribute() {
        return date('d.m.Y H:i',strtotime($this->created_at));
    }

    // Шаблон опроса
    public function template()
    {
        return $this->belongsTo(QuizTemplate::class, 'template_id')->withTrashed();
    }

    // Ответы на вопросы
    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'survey_id');
    }

    // Ответ заказчика на вопрос из опроса
    public function client_question_answer($survey_id,$client_id,$question_id)
    {
        return QuizAnswer::where('survey_id',$survey_id)->where('client_id',$client_id)->where('question_id',$question_id)->first();
    }

    // Все участники опроса
    public function clients()
    {
        return $this->hasMany(QuizSurveyClient::class, 'survey_id')->with('client');
    }

    // Участники опроса, уже ответившие на него
    public function clients_completed()
    {
        return $this->clients()->whereNotNull('completed_at');
    }

}
