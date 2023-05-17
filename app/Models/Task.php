<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table = 'task';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

    // Дата создания в текстовом формате
    public function getCreatedStrAttribute() {
        return date('d.m.Y H:i',strtotime($this->created_at));
    }

    // Дата завершения в текстовом формате
    public function getClosedStrAttribute() {
        return date('d.m.Y H:i',strtotime($this->closed_at));
    }

    // Сотрудник, создавший задачу
    public function manager_created()
    {
        return $this->belongsTo(User::class, 'manager_created_id')->withTrashed();
    }

    // Сотрудник, завершивший задачу
    public function manager_closed()
    {
        return $this->belongsTo(User::class, 'manager_closed_id')->withTrashed();
    }

    // Ответственный
    protected function responsible(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (empty($value)) ? [] :  explode(',',$value),
            set: fn ($value) => (empty($value)) ? NULL : implode(',',$value),
        );
    }
    public function getResponsibleNameAttribute(){
        $arResponsibleNames = [];
        $arResponsibles = User::whereIn('id',$this->responsible)->orderBy('name')->get();
        if (!empty($arResponsibles) && count($arResponsibles)) {
            foreach ($arResponsibles as $responsible) $arResponsibleNames[] = $responsible->name;
        }
        return implode(', ',$arResponsibleNames);
    }


    // Напоминать о задаче
    protected function remember(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (empty($value)) ? [] :  json_decode($value,true),
            set: fn ($value) => (empty($value)) ? NULL : json_encode($value),
        );
    }

    // Данные о просмотре сотрудниками
    protected function viewed(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (empty($value)) ? [] :  json_decode($value,true),
            set: fn ($value) => (empty($value)) ? NULL : json_encode($value),
        );
    }

    // Файлы, прикреплённые к задаче
    public function task_files()
    {
        return $this->hasMany(TaskFiles::class, 'task_id');
    }

}
