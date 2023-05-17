<?php

namespace App\Http\Controllers\Manager\Manager;

use App\Http\Controllers\Controller;
use App\Http\Requests\Manager\Manager\UpdateRequest;
use App\Models\User;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, User $manager)
    {
        $data = $request->validated();

        // Просмотр объектов на гарантии
        if (empty($data['show_ob_status_2'])) $data['show_ob_status_2'] = null;

        // Просмотр завершённых объектов
        if (empty($data['show_ob_status_3'])) $data['show_ob_status_3'] = null;

        // Веб-камера
        if (empty($data['show_webcam'])) {
            $data['show_webcam'] = null;
            $data['edit_webcam'] = null;
        }
        if (empty($data['edit_webcam'])) $data['edit_webcam'] = null;

        // Фото
        if (empty($data['show_photo'])) {
            $data['show_photo'] = null;
            $data['edit_photo'] = null;
        }
        if (empty($data['edit_photo'])) $data['edit_photo'] = null;

        // План работ
        if (empty($data['show_plan'])) {
            $data['show_plan'] = null;
            $data['edit_task'] = null;
            $data['delete_task'] = null;
        }
        if (empty($data['edit_task'])) $data['edit_task'] = null;
        if (empty($data['delete_task'])) $data['delete_task'] = null;

        // Смета
        if (empty($data['show_estimate'])) {
            $data['show_estimate'] = null;
            $data['edit_estimate'] = null;
        }
        if (empty($data['edit_estimate'])) $data['edit_estimate'] = null;

        // Смета для мастера
        if (empty($data['show_master_estimate'])) {
            $data['show_master_estimate'] = null;
            $data['edit_master_estimate'] = null;
        }
        if (empty($data['edit_master_estimate'])) $data['edit_master_estimate'] = null;

        // Чат
        if (empty($data['show_chat'])) {
            $data['show_chat'] = null;
            $data['edit_chat'] = null;
        }
        if (empty($data['edit_chat'])) $data['edit_chat'] = null;

        // Тех док
        if (empty($data['show_tech_doc'])) {
            $data['show_tech_doc'] = null;
            $data['edit_tech_doc'] = null;
            $data['delete_tech_doc'] = null;
        }
        if (empty($data['edit_tech_doc'])) $data['edit_tech_doc'] = null;
        if (empty($data['delete_tech_doc'])) $data['delete_tech_doc'] = null;

        // Расходы по объектам
        if (empty($data['show_expenses_object'])) {
            $data['show_expenses_object'] = null;
            $data['edit_expenses_object'] = null;
            $data['edit_expenses_object_all'] = null;
            $data['edit_expenses_object_estimate'] = null;
        }
        if (empty($data['edit_expenses_object'])) {
            $data['edit_expenses_object'] = null;
            $data['edit_expenses_object_all'] = null;
        }
        if (empty($data['edit_expenses_object_all'])) $data['edit_expenses_object_all'] = null;
        if (empty($data['edit_expenses_object_estimate'])) $data['edit_expenses_object_estimate'] = null;

        // Отчёт по расходам
        if (empty($data['show_expenses_personal'])) {
            $data['show_expenses_personal'] = null;
            $data['create_expense_income'] = null;
            $data['show_all_expenses_personal'] = null;
            $data['edit_all_expenses_personal'] = null;
        }
        if (empty($data['create_expense_income'])) $data['create_expense_income'] = null;
        if (empty($data['show_all_expenses_personal'])) {
            $data['show_all_expenses_personal'] = null;
            $data['edit_all_expenses_personal'] = null;
        }
        if (empty($data['edit_all_expenses_personal'])) $data['edit_all_expenses_personal'] = null;

        // Отчёт по материалам
        if (empty($data['show_expenses_mat_report'])) $data['show_expenses_mat_report'] = null;

        // Аналитика звонков
        if (empty($data['show_calls'])) {
            $data['show_calls'] = null;
            $data['edit_calls'] = null;
        }
        if (empty($data['edit_calls'])) $data['edit_calls'] = null;

        // Опросы
        if (empty($data['show_quiz'])) $data['show_quiz'] = null;
        if (empty($data['create_survey'])) $data['create_survey'] = null;
        if (empty($data['edit_quiz'])) $data['edit_quiz'] = null;

        $manager->update($data);
        return redirect()
            ->route('manager.manager.edit', $manager->id)
            ->with('notification',[
                'class' => 'success',
                'message' => 'Данные успешно обновлены'
            ]);
    }
}
