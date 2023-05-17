<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

// Проверка обновления чата
class CheckChatUpdateController extends Controller
{
    public function __invoke(Request $request)
    {
        $client_id = (int) $request->input('client_id');
        $chat_updated_at = (int) $request->input('chat_updated_at');
        if (!empty($client_id)) $client = User::find($client_id);
        if (!empty($client) && !empty($client->chat_updated_at_int) && $client->chat_updated_at_int > $chat_updated_at) {
            return response(1);
        }
        return response(0);
    }
}
