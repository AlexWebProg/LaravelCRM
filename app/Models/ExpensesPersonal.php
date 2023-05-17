<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensesPersonal extends Model
{
    use HasFactory;
    protected $table = 'expenses_personal';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

    // Дата создания в текстовом формате
    public function getDateStrAttribute() : string {
        return (empty($this->date)) ? '' : date('d.m.Y',strtotime($this->date));
    }

    // Сумма в текстовом формате
    public function getSumStrAttribute() : string {
        return formatMoney($this->sum);
    }

    // Сумма в рублях
    public function getSumRubAttribute() : string {
        return kopToRub($this->sum);
    }

    // Возможные категории расходов
    public static function getAvailableCategories() : array {
        return [
            0 => 'Приход',
            1 => 'Расход на автомобиль',
            3 => 'Зарплата техконтролю',
            4 => 'Передача другому сотруднику',
            2 => 'Другой расход',
        ];
    }

    // Категория расхода текстом
    public function getCategoryStrAttribute() : string {
        return self::getAvailableCategories()[$this->category];
    }

    // Сотрудник
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id')->withTrashed();
    }

    // Кому сделана передача
    public function transfer_to_manager()
    {
        return $this->belongsTo(User::class, 'transfer_to')->withTrashed();
    }

    // Заголовок расхода
    public function getNameAttribute() : string {
        return $this->category_str  . ' ' . $this->sum_str . ' от ' .  $this->date_str;
    }

    // Описание расхода
    public function getDescriptionAttribute() : string {
        return empty($this->comment) ? 'пояснение отсутствует' : $this->comment;
    }

    // Автор подробнее
    public function getAuthorExtendedAttribute() : string {
        return (!empty($this->manager?->name)) ? $this->manager->name : 'автор не указан';
    }

    // Передал: кто
    public function getTransferFromNameAttribute() : string {
        return (!empty($this->manager?->name)) ? $this->manager->name : 'не указан';
    }

    // Передал: кому
    public function getTransferToNameAttribute() : string {
        return (!empty($this->transfer_to_manager?->name)) ? $this->transfer_to_manager->name : 'не указан';
    }

}
