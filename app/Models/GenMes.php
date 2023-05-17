<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenMes extends Model
{
    use HasFactory;
    protected $table = 'gen_mes';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

    // Автор
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id')->withTrashed();
    }

    // Автор подробнее
    public function getAuthorExtendedAttribute() : string {
        return (!empty($this->manager?->name)) ? $this->manager->name : 'автор не указан';
    }

    // Дата создания в текстовом формате
    public function getCreatedTextAttribute() {
        return (empty($this->edited_at)) ? date('d.m.Y H:i',strtotime($this->created_at)) : 'изменено '.date('d.m.Y H:i',strtotime($this->edited_at));
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

}
