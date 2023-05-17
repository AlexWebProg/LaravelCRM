<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatFiles extends Model
{
    use HasFactory;
    protected $table = 'chat_files';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

}
