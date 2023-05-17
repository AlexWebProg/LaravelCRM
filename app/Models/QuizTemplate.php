<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizTemplate extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'quiz_template';
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

    // Вопросы в шаблоне
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'template_id')->orderBy('sort','ASC');
    }

    // Опросы на основе шаблона
    public function surveys()
    {
        return $this->hasMany(QuizSurvey::class, 'template_id');
    }

    public function clients() {
        return $this->hasManyThrough(QuizSurveyClient::class, QuizSurvey::class, 'template_id', 'survey_id')->with('client');
    }

    // Участники опросов по шаблону, уже ответившие на него
    public function clients_completed()
    {
        return $this->clients()->whereNotNull('completed_at');
    }

    public function last_survey_date()
    {
        return $this->surveys->sortByDesc('created_at')->first()->created_at;
    }

    public function last_survey_date_str()
    {
        return date('d.m.Y H:i',strtotime($this->last_survey_date()));
    }

}
