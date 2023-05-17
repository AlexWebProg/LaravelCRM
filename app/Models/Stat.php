<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    use HasFactory;
    protected $table = 'stat';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

    // Иконка в статистике
    public function getStatIconAttribute() {
        switch ($this->action) {
            case 'Рабочий стол':
                return 'fa-home bg-indigo';
                break;
            case 'Веб-камера':
                return 'fa-video-camera bg-danger';
                break;
            case 'Фото':
                return 'fa-camera bg-orange';
                break;
            case 'План работ':
                return 'fa-info-circle bg-warning';
                break;
            case 'Смета':
                return 'fa-file-text-o bg-success';
                break;
            case 'Чат':
            case 'Отправлено сообщение в чате':
            case 'Изменено сообщение в чате':
            case 'Прочитано сообщение в чате':
            case 'Удалено сообщение в чате':
            case 'Ответ на опрос в чате':
                return 'fa-comments-o bg-lightblue';
                break;
            case 'Техническая документация':
            case 'Тех. док.: загружен документ':
            case 'Тех. док.: изменён документ':
            case 'Тех. док.: удалён документ':
                return 'fa-file-pdf-o bg-primary';
                break;
            case 'Оплата':
                return 'fa-credit-card bg-indigo';
                break;
            case 'Контакты сотрудников':
                return 'fa-phone bg-maroon';
            case 'Партнёры':
                return 'fa-bookmark-o bg-orange';
            case 'Частые вопросы':
                return 'fa-info-circle bg-success';
            case 'Авторизация':
                return 'fa-sign-in bg-pink';
                break;
            case 'Выход':
                return 'fa-sign-out bg-gray';
                break;
            default:
                return 'fa-clock-o bg-gray';
                break;
        }
    }

    // Дата создания в формате дд.мм.гггг чч:мм
    public function getCreatedStrAttribute() {
        return date('d.m.Y H:i',strtotime($this->created_at));
    }

    // Дата создания в текстовом формате
    public function getCreatedTextAttribute() {
        return Carbon::parse($this->created_at)->isoFormat('D MMMM YYYY');
    }

}
