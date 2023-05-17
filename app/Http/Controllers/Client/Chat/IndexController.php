<?php

namespace App\Http\Controllers\Client\Chat;

use App\Models\ChatDraft;
use App\Models\Stat;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function __invoke()
    {
        Stat::create(['user_id' => auth()->user()->id, 'action' => 'Чат']);
        // Черновик чата
        $chat_draft = ChatDraft::where(['client_id' => auth()->user()->id, 'manager_id' => null])->first();
        return view('client.chat.index',compact('chat_draft'));
    }
}
