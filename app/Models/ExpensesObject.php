<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensesObject extends Model
{
    use HasFactory;
    protected $table = 'expenses_object';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

    // Дата создания в текстовом формате
    public function getDateStrAttribute() : string {
        return (empty($this->date)) ? '' : date('d.m.Y',strtotime($this->date));
    }

    // Суммы по чекам в текстовом формате
    public function getChkAmountStrAttribute() : string {
        return formatMoney($this->chk_amount);
    }

    // Суммы по чекам в рублях
    public function getChkAmountRubAttribute() : string {
        return kopToRub($this->chk_amount);
    }

    // Суммы по мусору в текстовом формате
    public function getGarbAmountStrAttribute() : string {
        return formatMoney($this->garb_amount);
    }

    // Суммы по мусору в рублях
    public function getGarbAmountRubAttribute() : string {
        return kopToRub($this->garb_amount);
    }

    // Суммы по инструменту в текстовом формате
    public function getToolAmountStrAttribute() : string {
        return formatMoney($this->tool_amount);
    }

    // Суммы по инструменту в рублях
    public function getToolAmountRubAttribute() : string {
        return kopToRub($this->tool_amount);
    }

    // Суммы полученные в текстовом формате
    public function getReceivedSumStrAttribute() : string {
        return formatMoney($this->received_sum);
    }

    // Суммы полученные в рублях
    public function getReceivedSumRubAttribute() : string {
        return kopToRub($this->received_sum);
    }

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

    // Автор
    public function getAuthorAttribute() : string {
        return (!empty($this->manager?->name)) ? $this->manager->name : 'нет';
    }

    // Автор подробнее
    public function getAuthorExtendedAttribute() : string {
        return (!empty($this->manager?->name)) ? $this->manager->name : 'автор не указан';
    }

    // Название объекта или расход по гарантии
    public function getObjectNameOrGuaranteeAttribute() : string {
        return empty($this->client_id) ? 'Расходы по гарантии' : $this->client?->address;
    }

    // Описание (Чеки и доставка/подробно) в текстовом формате
    public function getGoodsSumDescriptionAttribute() : string {
        return empty($this->сhk_and_del_det) ? 'описание отсутствует' : $this->сhk_and_del_det;
    }

    // Пояснение по инструменту в текстовом формате
    public function getToolsDescriptionAttribute() : string {
        return empty($this->tool_comment) ? 'описание отсутствует' : $this->tool_comment;
    }

    // Описание (Оплата работ, кому и за что подробно) в текстовом формате
    public function getWorkPayDescriptionAttribute() : string {
        return empty($this->work_pay) ? 'описание отсутствует' : $this->work_pay;
    }

}
