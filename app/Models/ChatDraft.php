<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatDraft extends Model
{
    use HasFactory;
    protected $table = 'chat_draft';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = false;

}
