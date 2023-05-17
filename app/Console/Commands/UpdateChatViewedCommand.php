<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;

use Illuminate\Console\Command;

class UpdateChatViewedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:chat-viewed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update chat message viewed list';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $arErrors = [];
        DB::beginTransaction();
        try {
            $arUsers = DB::table('users')->get();
            $arUserInfo = [];
            foreach ($arUsers as $arUser) {
                $arUserInfo[$arUser->id] = $arUser;
            }
            $arChat = DB::table('chat')->get();
            foreach ($arChat as $arMes) {
                $arViewed = [];
                if (!empty($arMes->read_managers)) {
                    $arReadManagers = json_decode($arMes->read_managers,true);
                    if (count($arReadManagers)) {
                        foreach ($arReadManagers as $user_id => $arReadManager) {
                            $arViewed[$user_id] = [
                                'name' => (!empty($arUserInfo[$user_id]->name) && $arUserInfo[$user_id]->type === 1) ? $arUserInfo[$user_id]->name : $arReadManager['name'],
                                'date' => $arReadManager['date'],
                            ];
                        }
                    }
                }
                if (!empty($arMes->read_at)) {
                    $arViewed[$arMes->client_id] = [
                        'name' => 'Заказчик',
                        'date' => date('d.m.Y H:i',strtotime($arMes->read_at)),
                    ];
                } elseif (empty($arMes->manager_id)) {
                    $arViewed[$arMes->client_id] = [
                        'name' => 'Заказчик',
                        'date' => date('d.m.Y H:i',strtotime($arMes->created_at)),
                    ];
                }
                if (count($arViewed)) {
                    // Сортируем массив по дате
                    $arHelper = [];
                    foreach ($arViewed as $user_id => $arData) {
                        $arHelper[] = [
                            'user' => $user_id,
                            'name' => $arData['name'],
                            'date' => $arData['date'],
                            'sort' => strtotime($arData['date'])
                        ];
                    }
                    array_multisort(array_column($arHelper,'sort'), SORT_ASC, $arHelper);
                    $arSaveData = [];
                    foreach ($arHelper as $arData) {
                        $arSaveData[$arData['user']] = [
                            'name' => $arData['name'],
                            'date' => $arData['date']
                        ];
                    }

                    DB::table('chat')
                        ->where('id',$arMes->id)
                        ->update(['viewed' => json_encode($arSaveData,true)]);
                }
            }
        } catch (\Exception $exception) {
            $arErrors[] = $exception->getMessage();
        }

        // Завершаем или откатываем транзакцию
        if (count($arErrors)) {
            DB::rollBack();
        } else {
            DB::commit();
        }

        // Уведомляем пользователя
        if (count($arErrors)) {
            dd(implode(', ',$arErrors));
        } else {
            dd('Данные о прочтении сообщений успешно обновлены');
        }

    }
}
