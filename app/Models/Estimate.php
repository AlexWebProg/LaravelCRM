<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;
    protected $table = 'estimate';
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
