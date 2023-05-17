<?php

namespace App\Http\Controllers\Manager\GenMes;

use App\Http\Controllers\Controller;
use App\Models\GenMes;
use App\Models\Chat;

class EditController extends Controller
{
    public function __invoke(GenMes $gen_mes)
    {
        if (!empty($gen_mes->files)) {
            $gen_mes->files_list = Chat::where('gen_mes_id',$gen_mes->id)->first()->message_files;
        }
        return view('manager.gen_mes.edit',compact('gen_mes'));
    }
}
