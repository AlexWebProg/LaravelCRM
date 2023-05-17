<?php

namespace App\Http\Controllers\Manager\GenMes;

use App\Http\Controllers\Controller;
use App\Models\GenMes;
use App\Models\Chat;

class CreateController extends Controller
{
    public function __invoke()
    {
        $sent = GenMes::orderBy('created_at','desc')->get();
        if (count($sent)) {
            foreach ($sent as &$mes) {
                if (!empty($mes->files)) {
                    $mes->files_list = Chat::where('gen_mes_id',$mes->id)->first()->message_files;
                }
            }
        }
        return view('manager.gen_mes.create',compact('sent'));
    }
}
