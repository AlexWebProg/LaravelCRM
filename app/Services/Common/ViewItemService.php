<?php

namespace App\Services\Common;

use App\Models\Estimate;
use App\Models\EstimateComment;
use App\Models\MasterEstimate;
use App\Models\MasterEstimateComment;
use App\Models\Photo;
use App\Models\Task;
use App\Models\TechDoc;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Support\Facades\Gate;

class ViewItemService
{
    // Добавляет пользователя в массив просмотра элемента
    public function userViewedItem(array &$arViewedUsers)
    {
        $arViewedUsers[auth()->user()->id] = [
            'name' => (!empty(auth()->user()->type) && auth()->user()->type === 'manager') ? auth()->user()->name : 'Заказчик',
            'date' => date('d.m.Y H:i',time()),
        ];
    }

    // Формирует данные для popover о просмотре элемента пользователями
    public function makeViewedUsersList(object $item, array $arViewedUsers, bool $bManagersOnly = false) : string
    {
        $strPopover = '<ul class="list-group list-group-flush">';
        $strPopover .= '<li class="list-group-item viewed_popover_header viewed_popover_header_viewed bg-primary d-flex justify-content-between align-items-center">Просмотрено<i class="fa fa-chevron-up" aria-hidden="true"></i></li>';
        $arViewedManagerIDs = [];
        if (count($arViewedUsers)) {
            foreach ($arViewedUsers as $intViewedUserID => $arViewedUser) {
                if ($arViewedUser['name'] === 'Заказчик') {
                    if (!$bManagersOnly) {
                        $strPopover .= '<li class="list-group-item text-primary px-0 viewed_popover_item_viewed">' . $arViewedUser['name'] . ' <small class="text-muted">' . $arViewedUser['date'] . '</small></li>';
                        $bClientViewed = true;
                    }
                } else {
                    $strPopover .= '<li class="list-group-item px-0 viewed_popover_item_viewed">' . $arViewedUser['name'] . ' <small class="text-muted">' . $arViewedUser['date'] . '</small></li>';
                    $arViewedManagerIDs[] = $intViewedUserID;
                }
            }
        } else {
            $strPopover .= '<li class="list-group-item px-0 viewed_popover_item_viewed">нет</li>';
        }
        $strPopover .= '<li class="list-group-item viewed_popover_header viewed_popover_header_unviewed bg-secondary d-flex justify-content-between align-items-center">Не просмотрено<i class="fa fa-chevron-up ml-2" aria-hidden="true"></i></li>';
        if (empty($bClientViewed) && !$bManagersOnly) {
            $strPopover .= '<li class="list-group-item text-primary px-0 viewed_popover_item_unviewed">Заказчик</li>';
        }
        $arNotViewedUsers = User::where('is_active', 1)
            ->where('type', 1)
            ->whereNotIN('id',$arViewedManagerIDs)
            ->orderBy('name')
            ->get();
        // В списке непросмотревших оставляем только тех, у кого есть разрешение на просмотр объекта
        if (count($arNotViewedUsers)) {
            foreach ($arNotViewedUsers as $k => $notViewedUser) {
                if ($item instanceof Chat && !Gate::forUser($notViewedUser)->allows('show_chat')) unset($arNotViewedUsers[$k]);
                if ($item instanceof Estimate && !Gate::forUser($notViewedUser)->allows('show_estimate')) unset($arNotViewedUsers[$k]);
                if ($item instanceof EstimateComment && !Gate::forUser($notViewedUser)->allows('show_estimate')) unset($arNotViewedUsers[$k]);
                if ($item instanceof MasterEstimate && !Gate::forUser($notViewedUser)->allows('show_master_estimate')) unset($arNotViewedUsers[$k]);
                if ($item instanceof MasterEstimateComment && !Gate::forUser($notViewedUser)->allows('show_master_estimate')) unset($arNotViewedUsers[$k]);
                if ($item instanceof Photo && !Gate::forUser($notViewedUser)->allows('show_photo')) unset($arNotViewedUsers[$k]);
                if ($item instanceof TechDoc && !Gate::forUser($notViewedUser)->allows('show_tech_doc')) unset($arNotViewedUsers[$k]);
                if ($item instanceof Task && !Gate::forUser($notViewedUser)->allows('show_plan')) unset($arNotViewedUsers[$k]);
            }
        }
        if (count($arNotViewedUsers)) {
            foreach ($arNotViewedUsers as $notViewedUser) {
                $strPopover .= '<li class="list-group-item px-0 viewed_popover_item_unviewed">' . $notViewedUser->name . '</li>';
            }
        } elseif (!empty($bClientViewed) || $bManagersOnly) {
            $strPopover .= '<li class="list-group-item px-0 viewed_popover_item_unviewed">нет</li>';
        }
        $strPopover .= '</ul>';
        return $strPopover;
    }

    // Добавляет пользователя в массив просмотра элемента и формирует данные для popover о просмотре элемента
    public function makeItemViewed(object &$item, bool $bManagersOnly = false){
        $arViewedUsers = $item->viewed;
        if (empty($arViewedUsers[auth()->user()->id])) {
            $this->userViewedItem($arViewedUsers);
            $item->update(['viewed' => $arViewedUsers]);
            $item->un_viewed = 1;
        }
        $item->popover = $this->makeViewedUsersList($item, $arViewedUsers, $bManagersOnly);
    }

    // Формирует данные для popover функций сообщения
    public function makeChatMessageFunctionsList(object &$message)
    {
        $arFunctions = [];
        if (!empty(auth()->user()->type) && auth()->user()->type === 'manager') {
            $arFunctions[] = '<li class="list-group-item viewed"><i class="fa fa-info-circle mr-2" aria-hidden="true"></i>Просмотры</li>';
        }
        if (empty($message->deleted_at)) {
            $arFunctions[] = '<li class="list-group-item copy_message"><i class="fa fa-copy mr-2" aria-hidden="true"></i>Копировать</li>';
            $arFunctions[] = '<li class="list-group-item reply_message"><i class="fa fa-reply mr-2" aria-hidden="true"></i>Ответить</li>';
            if (Gate::allows('edit-chat', $message)) {
                $arFunctions[] = '<li class="list-group-item edit_message"><i class="fa fa-pencil-square-o mr-2" aria-hidden="true"></i>Изменить</li>';
                $arFunctions[] = '<li class="list-group-item delete_message"><i class="fa fa-trash-o mr-2" aria-hidden="true"></i>Удалить</li>';
            }
        }
        $strFunctions = '';
        if (count($arFunctions)) {
            $strFunctions .= '<ul class="list-group list-group-flush" data-message_id="'.$message->id.'">'.implode('',$arFunctions).'</ul>';
        }
        $message->functions = $strFunctions;
    }
}
