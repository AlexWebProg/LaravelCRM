<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatDraft;
use App\Http\Requests\Api\ChatDraftStoreRequest;

// Проверка обновления чата
class ChatDraftStoreController extends Controller
{
    public function __invoke(ChatDraftStoreRequest $request)
    {
        $data = $request->validated();
        $chat_draft = ChatDraft::firstOrCreate(['client_id' => $data['client_id'], 'manager_id' => $data['manager_id']]);
        ChatDraft::where('id', $chat_draft->id)->update($data);
    }
}
