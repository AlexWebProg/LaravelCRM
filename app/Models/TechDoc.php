<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechDoc extends Model
{
    use HasFactory;
    protected $table = 'tech_doc';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;
    protected $with = [
        'client', // Объект
    ];

    // Дата создания в формате дд.мм.гггг чч:мм
    public function getCreatedStrAttribute() {
        return date('d.m.Y H:i',strtotime($this->created_at));
    }

    // Текст сообщения с кликабельными ссылками
    public function getCommentUrlifiedAttribute()
    {
        if (empty($this->comment)) return 'отсутствует';
        $url = '/(http|https|)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';
        return preg_replace($url, '<a href="$0" target="_blank">$0</a>', $this->comment);
    }

    // Данные о прочтении заказчиком и сотрудниками
    protected function viewed(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (empty($value)) ? [] :  json_decode($value,true),
            set: fn ($value) => (empty($value)) ? NULL : json_encode($value),
        );
    }

    // Объект
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->withTrashed();
    }

    // Сотрудник
    public function manager()
    {
        return $this->belongsTo(User::class, 'uploaded_manager_id')->withTrashed();
    }

}
