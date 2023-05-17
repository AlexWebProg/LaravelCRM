<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskFiles extends Model
{
    use HasFactory;
    protected $table = 'task_files';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = true;

}
