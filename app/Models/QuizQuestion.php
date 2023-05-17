<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizQuestion extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'quiz_question';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

}
