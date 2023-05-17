<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $table = 'partners';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

    // Телефон в текстовом формате
    public function getPhoneStrAttribute() {
        return phoneMask($this->phone);
    }
}
