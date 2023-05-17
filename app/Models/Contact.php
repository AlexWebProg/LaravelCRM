<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $table = 'contacts';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

    // Телефон в текстовом формате
    public function getPhoneStrAttribute() {
        return phoneMask($this->phone);
    }
}
