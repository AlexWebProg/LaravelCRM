<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateComment extends Model
{
    use HasFactory;
    protected $table = 'estimate_comment';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

    // Данные о прочтении заказчиком и сотрудниками
    protected function viewed(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (empty($value)) ? [] :  json_decode($value,true),
            set: fn ($value) => (empty($value)) ? NULL : json_encode($value),
        );
    }

    // Сотрудник
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id')->withTrashed();
    }

    // Дата обновления в формате дд.мм.гггг чч:мм
    public function getUpdatedStrAttribute() {
        return date('d.m.Y H:i',strtotime($this->updated_at));
    }

}
