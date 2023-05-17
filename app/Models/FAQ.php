<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    use HasFactory;
    protected $table = 'faq';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

    // Текст вопроса с кликабельными ссылками
    public function getQuestionUrlifiedAttribute()
    {
        $url = '/(http|https|)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';
        return preg_replace($url, '<a href="$0" class="faq_link" target="_blank">$0</a>', $this->question);
    }

    // Текст ответа с кликабельными ссылками
    public function getAnswerUrlifiedAttribute()
    {
        $url = '/(http|https|)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';
        return preg_replace($url, '<a href="$0" class="faq_link" target="_blank">$0</a>', $this->answer);
    }

}
