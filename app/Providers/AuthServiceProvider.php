<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\ExpensesObject;
use App\Models\ExpensesPersonal;
use App\Models\User;
use App\Models\Photo;
use App\Models\Chat;
use App\Models\Task;
use App\Models\TechDoc;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Просмотр объектов на гарантии
        Gate::define('show_ob_status_2', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->show_ob_status_2));
        });

        // Просмотр завершённых объектов
        Gate::define('show_ob_status_3', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->show_ob_status_3));
        });

        // Просмотр веб-камеры
        Gate::define('show_webcam', function (User $user) {
            return ($user->is_active && ($user->is_admin || ($user->type && $user->show_webcam)));
        });

        // Управление веб-камерой
        Gate::define('edit_webcam', function (User $user) {
            return ($user->is_active && ($user->is_admin || ($user->type && $user->edit_webcam)));
        });

        // Просмотр фото и видео в карточках объектов, редактирование и удаление своих фото и видео в течение 10 минут после загрузки
        Gate::define('show_photo', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->show_photo));
        });

        // Удаление фото: руководитель или признак - любое фото в любое время, загрузивший фото сотрудник - своё фото в теч 10 минут
        Gate::define('delete-photo', function (User $user, Photo $photo) {
            return (
                $user->is_admin ||
                $user->edit_photo ||
                (
                    !empty($photo->uploaded_manager_id) &&
                    $photo->uploaded_manager_id === $user->id &&
                    strtotime($photo->created_at) > time() - 600
                )
            );
        });

        // Просмотр планов работ в карточках объектов, создание задач и управление своими задачами
        Gate::define('show_plan', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->show_plan));
        });

        // Редактирование задач в плане работ: руководитель или признак - любая задача, сотрудник - только своя
        Gate::define('edit_task', function (User $user, Task $task) {
            return (
                $user->is_admin ||
                $user->edit_task ||
                (
                    !empty($task->manager_created_id) &&
                    $task->manager_created_id === $user->id
                )
            );
        });

        // Завершение задач в плане работ: руководитель или признак - любая задача, сотрудник - только своя, ответственный - та, за которую он ответственен
        Gate::define('complete_task', function (User $user, Task $task) {
            return (
                $user->is_admin ||
                $user->edit_task ||
                (
                    !empty($task->manager_created_id) &&
                    $task->manager_created_id === $user->id
                )
                || in_array($user->id,$task->responsible)
            );
        });

        // Удаление задач в плане работ: руководитель или признак - любая задача
        Gate::define('delete_task', function (User $user) {
            return ($user->is_admin || $user->delete_task);
        });

        // Просмотр и загрузка смет
        Gate::define('show_estimate', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->show_estimate));
        });

        // Удаление смет
        Gate::define('edit_estimate', function (User $user) {
            return ($user->is_active && ($user->is_admin || ($user->type && $user->edit_estimate)));
        });

        // Просмотр и загрузка смет для мастера
        Gate::define('show_master_estimate', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->show_master_estimate));
        });

        // Удаление смет для мастера
        Gate::define('edit_master_estimate', function (User $user) {
            return ($user->is_active && ($user->is_admin || ($user->type && $user->edit_master_estimate)));
        });

        // Чат: Просмотр и отправка сообщений, редактирование и удаление своих сообщений до прочтения заказчиком и в течение 5 минут после
        Gate::define('show_chat', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->show_chat));
        });

        // Удаление и редактирование сообщений в чате: заказчики - 5 мин после отправки, сотрудники и руководство: пока заказчик не прочитал и 5 минут после прочтения
        // Сотрудники - только свои сообщения, руководство или спец признак - также сообщения других сотрудников
        Gate::define('edit-chat', function (User $user, Chat $chat) {
            return (
                !empty($user->type) && empty($chat->deleted_at) &&
                (
                    ( // Сотрудник или руководитель
                        $user->type === 'manager' && !empty($chat->manager_id) &&
                        ($user->is_admin || $user->edit_chat || $chat->manager_id === $user->id) &&
                        (empty($chat->viewed[$chat->client_id]) || strtotime($chat->viewed[$chat->client_id]['date']) > time() - 300)
                    )
                    ||
                    ( // Заказчик
                        $user->type === 'client' && $chat->manager_id === null &&
                        $chat->client_id === $user->id && strtotime($chat->created_at) > time() - 300
                    )
                )
            );
        });

        // Тех док: Просмотр, загрузка и редактирование своих технических документов
        Gate::define('show_tech_doc', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->show_tech_doc));
        });

        // Редактирование технических документов: руководитель или признак - любой документ, сотрудник - только загруженный собой
        Gate::define('edit_tech_doc', function (User $user, TechDoc $tech_doc) {
            return (
                $user->is_admin ||
                $user->edit_tech_doc ||
                (
                    !empty($tech_doc->uploaded_manager_id) &&
                    $tech_doc->uploaded_manager_id === $user->id
                )
            );
        });

        // Удаление технических документов: руководитель или признак - любой документ
        Gate::define('delete_tech_doc', function (User $user) {
            return ($user->is_active && ($user->is_admin || ($user->type && $user->delete_tech_doc)));
        });

        // Просмотр расходов по объектам
        Gate::define('show_expenses_object', function (User $user) {
            return ($user->is_active && ($user->is_admin || ($user->type && $user->show_expenses_object)));
        });

        // Редактирование хотя бы только своих расходов по объектам - для показа кнопок
        Gate::define('edit_expenses_object', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->edit_expenses_object));
        });

        // Редактирование конкретного расхода по объектам
        Gate::define('edit_current_expense_object', function (User $user, ExpensesObject $expense) {
            return (
                $expense->id && $user->is_active && $user->type &&
                (
                    $user->is_admin // Руководитель
                    || $user->edit_expenses_object_all // Может редактировать расходы других сотрудников
                    || ($expense->manager_id === $user->id && $user->edit_expenses_object) // Может редактировать свои расходы
                )
            );
        });

        // Редактирование сумм, заложенных для объекта по смете
        Gate::define('edit_expenses_object_estimate', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->edit_expenses_object_estimate));
        });

        // Доступен отчёт по своим приходам и расходам
        Gate::define('show_expenses_personal', function (User $user) {
            return ($user->is_active && ($user->is_admin || ($user->type && $user->show_expenses_personal)));
        });

        // Доступно создание приходов в отчёте по приходам и расходам
        Gate::define('create_expense_income', function (User $user, User $other_manager = null) {
            if (!empty($other_manager)) $user = $other_manager;
            return ($user->is_active && ($user->is_admin || ($user->type && $user->create_expense_income)));
        });

        // Просмотр отчётов по расходам других сотрудников
        Gate::define('show_all_expenses_personal', function (User $user) {
            return ($user->is_active && ($user->is_admin || ($user->type && $user->show_all_expenses_personal)));
        });

        // Редактирование отчётов по расходам других сотрудников
        Gate::define('edit_all_expenses_personal', function (User $user) {
            return ($user->is_active && ($user->is_admin || ($user->type && $user->edit_all_expenses_personal)));
        });

        // Редактирование конкретного расхода
        Gate::define('edit_current_expense_personal', function (User $user, ExpensesPersonal $expense) {
            return (
                $expense->id && $user->is_active && $user->type &&
                (
                    $user->is_admin // Руководитель
                    || $user->edit_all_expenses_personal // Может редактировать расходы всех сотрудников
                    || ($user->show_expenses_personal && $expense->manager_id === $user->id) // Расход - свой
                )
            );
        });

        // Доступен отчёт по материалам
        Gate::define('show_expenses_mat_report', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->show_expenses_mat_report));
        });

        // Аналитика звонков: Просмотр
        Gate::define('show_calls', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->show_calls));
        });

        // Аналитика звонков: Редактирование
        Gate::define('edit_calls', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->edit_calls));
        });

        // Опросы: Просмотр результатов
        Gate::define('show_quiz', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->show_quiz));
        });

        // Опросы: Проведение опросов
        Gate::define('create_survey', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->create_survey));
        });

        // Опросы: Редактирование шаблонов
        Gate::define('edit_quiz', function (User $user) {
            return ($user->is_active && $user->type && ($user->is_admin || $user->edit_quiz));
        });

    }
}
