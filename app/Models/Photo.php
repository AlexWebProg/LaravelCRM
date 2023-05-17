<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    protected $table = 'photo';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;
    protected $with = [
        'client', // Объект
        'manager', // Сотрудник
    ];

    // Дата создания в текстовом формате
    public function getCreatedStrAttribute() {
        return date('d.m.Y H:i',strtotime($this->created_at));
    }

    // Комментарий к фото
    public function getDescriptionAttribute() {
        $strDescription = '';
        if (!empty($this->comment)) $strDescription .= '<p class="px-0 pt-0 m-0">' . $this->comment . '</p>';
        if (!empty($this->created_str)) $strDescription .= '<p class="text-right p-0 m-0"><small>' . $this->created_str . '</small></p>';
        return $strDescription;
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
