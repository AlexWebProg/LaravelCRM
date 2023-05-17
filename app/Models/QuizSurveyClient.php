<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSurveyClient extends Model
{
    use HasFactory;
    protected $table = 'quiz_survey_client';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

    // Объект
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->withTrashed();
    }

}
