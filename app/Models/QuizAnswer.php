<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    use HasFactory;
    protected $table = 'quiz_answer';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

    // Заказчик
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id')->withTrashed();
    }

    // Дата создания в текстовом формате
    public function getCreatedStrAttribute() {
        return date('d.m.Y H:i',strtotime($this->created_at));
    }

    // Рейтинг звёздами
    public function getRatingStarAttribute() {
        $stars = '';
        if (!empty($this->rating)) {
            for ($star = 1; $star <= $this->rating; $star++) {
                $stars .= '<i class="fa fa-star text-info mr-1" aria-hidden="true"></i>';
            }
        }
        return $stars;
    }

    // Рейтинг звёздами с пустыми звёздами (4 из 5)
    public function rating_star_with_empty($rating_to,$bForChat=false,$bForManagePanel=false) {
        $stars = $this->rating_star;
        if ($rating_to > $this->rating) {
            for ($star = $this->rating + 1; $star <= $rating_to; $star++) {
                $stars .= '<i class="fa fa-star-o text-info mr-1" aria-hidden="true"></i>';
            }
        }
        if ($bForChat) {
            $stars = str_replace('fa fa-star','fa fa-lg fa-star',$stars);
        }
        if ($bForManagePanel) {
            $stars = str_replace('text-info','text-warning',$stars);
        }
        return $stars;
    }

}
