<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $table = 'chat';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;
    protected $with = [
        'client', // Объект
        'manager', // Сотрудник
        'replied_message', // Сообщение, на которое это сообщение является ответом
        'message_files', // Файлы, прикреплённые к сообщению
    ];

    // Объект
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->withTrashed();
    }

    // Сотрудник
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id')->withTrashed();
    }

    // Сообщение, на которое это сообщение является ответом
    public function replied_message()
    {
        return $this->belongsTo(Chat::class, 'reply_message_id');
    }

    // Файлы, прикреплённые к сообщению
    public function message_files()
    {
        return $this->hasMany(ChatFiles::class, 'message_id');
    }

    // Опрос
    public function survey()
    {
        return $this->belongsTo(QuizSurvey::class, 'quiz_survey_id');
    }

    // Дата создания в текстовом формате
    public function getCreatedTextAttribute() {
        return (empty($this->edited_at)) ? date('d.m.Y H:i',strtotime($this->created_at)) : 'изменено '.date('d.m.Y H:i',strtotime($this->edited_at));
    }

    // Дата изменения в текстовом формате
    public function getUpdatedTextAttribute() {
        return date('d.m.Y H:i',strtotime($this->updated_at));
    }

    // Автор сообщения для ЛК заказчика
    public function getAuthorAttribute()
    {
        $author = 'Автор неизвестен';
        if (!empty($this->manager_id)) {
            if (!empty($this->manager) && !empty($this->manager->name)) {
                $author = $this->manager->name;
            }
        } elseif (!empty($this->client_id)) {
            if (!empty($this->client) && !empty($this->client->name)) {
                $author = $this->client->name;
            }
        }
        return $author;
    }

    // Автор сообщения для панели управления
    public function getAuthorMpAttribute()
    {
        $author = 'Автор неизвестен';
        if (!empty($this->manager_id)) {
            if (!empty($this->manager) && !empty($this->manager->name)) {
                $author = $this->manager->name;
            }
        } elseif (!empty($this->client_id)) {
            if (!empty($this->client) && !empty($this->client->name)) {
                $author = $this->client->name . ' (Заказчик)';
            }
        }
        return $author;
    }

    // Данные о прочтении заказчиком и сотрудниками
    protected function viewed(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (empty($value)) ? [] :  json_decode($value,true),
            set: fn ($value) => (empty($value)) ? NULL : json_encode($value),
        );
    }

    // Текст сообщения с кликабельными ссылками
    public function getTextUrlifiedAttribute()
    {
        $url = '/(http|https|)\:\/\/[а-яА-Яa-zA-Z0-9\-\.]+\.[а-яА-Яa-zA-Z]{2,10}(\/\S*)?/u';
        $phone = '/\+?[7-8][0-9()\-\s+]{11,16}|\+7[0-9]{10,11}/';
        $text = preg_replace($url, '<a href="$0" target="_blank">$0</a>', $this->text);
        $text = preg_replace($phone, '<a href="tel:$0">$0</a>', $text);
        return $text;
    }

    // Текст сообщения, если оно было удалено
    protected function text(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (empty($this->deleted_at)) ? $value :  '<span class="text-muted"><i class="fa fa-ban mr-2" aria-hidden="true"></i>Сообщение удалено</span>'
        );
    }

}
