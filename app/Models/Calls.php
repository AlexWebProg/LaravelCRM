<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calls extends Model
{
    use HasFactory;
    protected $table = 'calls';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

    // Сотрудник
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id')->withTrashed();
    }

    // Автор подробнее
    public function getAuthorExtendedAttribute() : string {
        return (!empty($this->manager?->name)) ? $this->manager->name : 'автор не указан';
    }

}
