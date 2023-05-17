<?php

use Illuminate\Support\Facades\Route;
use App\Models\ExpensesObject;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Авторизация
Auth::routes();

// Демо-авторизация
Route::get('/demo', 'App\Http\Controllers\Auth\LoginController@login_demo');

// ЛК Заказчика
Route::group(['namespace' => 'App\Http\Controllers\Client', 'middleware' => ['auth', 'user-access:client']], function () {
    // Рабочий стол
    Route::group(['namespace' => 'Main'], function () {
        Route::get('/','IndexController')->name('client.main');
    });
    // Фото
    Route::group(['namespace' => 'Photo', 'prefix' => 'photos'], function () {
        Route::get('/','IndexController')->name('client.photos');
        Route::get('/photo_list','PhotoListController');
    });
    // План
    Route::group(['namespace' => 'Plan', 'prefix' => 'plan'], function () {
        Route::get('/','IndexController')->name('client.plan');
    });
    // Смета
    Route::group(['namespace' => 'Estimate', 'prefix' => 'estimate'], function () {
        Route::get('/','IndexController')->name('client.estimate');
    });
    // Чат
    Route::group(['namespace' => 'Chat', 'prefix' => 'chat'], function () {
        Route::get('/','IndexController')->name('client.chat');
        Route::get('/message_list','MessageListController');
        Route::put('/add_message','StoreController')->name('client.chat.store')->middleware('user-access:not_demo');
        Route::patch('/delete_message','DeleteController')->name('client.chat.delete')->middleware('user-access:not_demo');
        Route::put('/survey','SurveyController')->name('client.chat.survey')->middleware('user-access:not_demo');
    });
    // Техническая документация
    Route::group(['namespace' => 'TechDoc', 'prefix' => 'tech_doc'], function () {
        Route::get('/','IndexController')->name('client.tech_doc');
        Route::put('/','StoreController')->name('client.tech_doc.store')->middleware('user-access:not_demo');
        Route::patch('/','UpdateController')->name('client.tech_doc.update')->middleware('user-access:not_demo');
        Route::delete('/{tech_doc}','DeleteController')->name('client.tech_doc.delete')->middleware('user-access:not_demo');
    });
    // Контакты сотрудников
    Route::group(['namespace' => 'Contact', 'prefix' => 'contact'], function () {
        Route::get('/','IndexController')->name('client.contact');
    });
    // Партнёры
    Route::group(['namespace' => 'Partner', 'prefix' => 'partner'], function () {
        Route::get('/','IndexController')->name('client.partner');
    });
    // Частые вопросы
    Route::group(['namespace' => 'FAQ', 'prefix' => 'faq'], function () {
        Route::get('/','IndexController')->name('client.faq');
    });
    // Статистика
    Route::group(['namespace' => 'Stat', 'prefix' => 'stat'], function () {
        Route::get('/{action}','StoreController');
    });
});

// Панель управления
Route::group(['namespace' => 'App\Http\Controllers\Manager', 'middleware' => ['auth', 'user-access:manager'], 'prefix' => 'manage'], function () {
    // Рабочий стол - делает переадресацию на страницу "объекты"
    Route::group(['namespace' => 'Main'], function () {
        Route::get('/','IndexController')->name('manager.main');
    });
    // Объекты
    Route::group(['namespace' => 'Client', 'prefix' => 'client'], function () {
        Route::get('/status/{ob_status}','IndexController')->name('manager.client.index');

        Route::get('/create','CreateController')->name('manager.client.create')->middleware('user-access:admin');
        Route::put('/','StoreController')->name('manager.client.store')->middleware('user-access:admin');

        Route::get('/{client}/edit/{action_type?}','EditController')->name('manager.client.edit');
        Route::patch('/{client}','UpdateController')->name('manager.client.update')->middleware('user-access:admin');

        // Веб-камера
        Route::patch('/{client}/webcam','WebCamController')->name('manager.client.webcam')->can('edit_webcam');

        // Фото
        Route::put('/{client}/photo_upload','PhotoUploadController')->name('manager.client.photo.upload')->can('show_photo');
        Route::patch('/{client}/photo_update','PhotoUpdateController')->name('manager.client.photo.update')->can('show_photo');
        Route::get('/{client}/photo_list','PhotoIndexController')->can('show_photo');
        Route::post('/{client}/photo_delete_by_src','PhotoDeleteBySrcController')->can('show_photo');

        // План работ
        Route::put('/{client}/task_store','TaskStoreController')->name('manager.client.task.store')->can('show_plan');
        Route::patch('/{client}/task_update','TaskUpdateController')->name('manager.client.task.update')->can('show_plan');
        Route::post('/{client}/task_file_delete_by_src','TaskFileDeleteBySrcController')->can('show_plan');
        Route::delete('/{client}/task_delete','TaskDeleteController')->name('manager.client.task.delete')->can('delete_task');

        // Смета
        Route::put('/{client}/estimate_upload','EstimateUploadController')->name('manager.client.estimate.upload')->can('show_estimate');
        Route::patch('/{client}/estimate_comment_update','EstimateCommentUpdateController')->name('manager.client.estimate_comment.update')->can('show_estimate');
        Route::delete('/{client}/estimate_delete/{estimate}','EstimateDeleteController')->name('manager.client.estimate.delete')->can('edit_estimate');

        // Смета для мастера
        Route::put('/{client}/master_estimate_upload','MasterEstimateUploadController')->name('manager.client.master_estimate.upload')->can('show_master_estimate');
        Route::patch('/{client}/master_estimate_comment_update','MasterEstimateCommentUpdateController')->name('manager.client.master_estimate_comment.update')->can('show_master_estimate');
        Route::delete('/{client}/master_estimate_delete/{master_estimate}','MasterEstimateDeleteController')->name('manager.client.master_estimate.delete')->can('edit_master_estimate');

        // Чат
        Route::get('/{client}/chat_message_list','ChatMessageListController')->can('show_chat');
        Route::put('/{client}/chat_add_message','ChatStoreController')->name('manager.client.chat.store')->can('show_chat');
        Route::patch('/{client}/chat_delete_message','ChatMessageDeleteController')->name('manager.client.chat.delete')->can('show_chat');
        Route::get('/{client}/chat_remember/{remember}','ChatRememberController')->name('manager.client.chat_remember')->can('show_chat');

        // Тех док
        Route::put('/{client}/tech_doc_store','TechDocStoreController')->name('manager.client.tech_doc.store')->can('show_tech_doc');
        Route::patch('/{client}/tech_doc_update','TechDocUpdateController')->name('manager.client.tech_doc.update')->can('show_tech_doc');
        Route::delete('/{client}/tech_doc_delete/{tech_doc}','TechDocDeleteController')->name('manager.client.tech_doc.delete')->can('delete_tech_doc');

        Route::delete('/{client}','DeleteController')->name('manager.client.delete')->middleware('user-access:admin');
    });
    // Расходы сотрудника
    Route::group(['namespace' => 'ExpensesPersonal', 'prefix' => 'expenses_personal'], function () {
        Route::get('/','IndexController')->name('manager.expenses_personal.index')->can('show_expenses_personal');
        Route::get('/create','CreateController')->name('manager.expenses_personal.create')->can('show_expenses_personal');
        Route::put('/','StoreController')->name('manager.expenses_personal.store')->can('show_expenses_personal');
        Route::get('/edit/{expense}','EditController')->name('manager.expenses_personal.edit')->can('edit_current_expense_personal,expense');
        Route::patch('/{expense}','UpdateController')->name('manager.expenses_personal.update')->can('edit_current_expense_personal,expense');
        Route::delete('/{expense}','DeleteController')->name('manager.expenses_personal.delete')->can('edit_current_expense_personal,expense');
    });
    // Расходы всех сотрудников
    Route::group(['namespace' => 'ExpensesAllPersonal', 'prefix' => 'expenses_all_personal'], function () {
        Route::get('/','ManagersIndexController')->name('manager.expenses_all_personal.managers_index')->can('show_all_expenses_personal');
        Route::get('/{manager}','ExpensesIndexController')->name('manager.expenses_all_personal.expenses_index')->can('show_all_expenses_personal');
        Route::get('/{manager}/create','CreateController')->name('manager.expenses_all_personal.create')->can('edit_all_expenses_personal');
        Route::get('/{manager}/{expense}/edit','EditController')->name('manager.expenses_all_personal.edit')->can('edit_all_expenses_personal');
    });
    // Объекты: сводка
    Route::group(['namespace' => 'ClientSummary', 'prefix' => 'client_summary'], function () {
        Route::get('/','IndexController')->name('manager.client_summary.index');
        Route::post('/update','UpdateController');
        Route::get('/check_updated','CheckUpdatedController');
        Route::get('/export_excel','ExportExcelController')->name('manager.client_summary.export_excel');
    });
    // Сотрудники и руководство
    Route::group(['namespace' => 'Manager', 'prefix' => 'manager', 'middleware' => ['user-access:admin']], function () {
        Route::get('/{is_admin?}','IndexController')->name('manager.manager.index');
        Route::get('/create/admin/{is_admin?}','CreateController')->name('manager.manager.create');
        Route::put('/','StoreController')->name('manager.manager.store');
        Route::get('/{manager}/edit/{action_type?}','EditController')->name('manager.manager.edit');
        Route::patch('/{manager}','UpdateController')->name('manager.manager.update');
        Route::delete('/{manager}','DeleteController')->name('manager.manager.delete');
    });
    // Контакты сотрудников
    Route::group(['namespace' => 'Contact', 'prefix' => 'contact'], function () {
        Route::get('/','IndexController')->name('manager.contact.index');

        Route::get('/create','CreateController')->name('manager.contact.create')->middleware('user-access:admin');
        Route::put('/','StoreController')->name('manager.contact.store')->middleware('user-access:admin');

        Route::get('/{contact}/edit','EditController')->name('manager.contact.edit');
        Route::patch('/{contact}','UpdateController')->name('manager.contact.update')->middleware('user-access:admin');

        Route::delete('/{contact}','DeleteController')->name('manager.contact.delete')->middleware('user-access:admin');
    });
    // Партнёры
    Route::group(['namespace' => 'Partner', 'prefix' => 'partner'], function () {
        Route::get('/','IndexController')->name('manager.partner.index');

        Route::get('/create','CreateController')->name('manager.partner.create')->middleware('user-access:admin');
        Route::put('/','StoreController')->name('manager.partner.store')->middleware('user-access:admin');

        Route::get('/{partner}/edit','EditController')->name('manager.partner.edit')->middleware('user-access:admin');
        Route::patch('/{partner}','UpdateController')->name('manager.partner.update')->middleware('user-access:admin');

        Route::delete('/{partner}','DeleteController')->name('manager.partner.delete')->middleware('user-access:admin');
    });
    // Частые вопросы
    Route::group(['namespace' => 'FAQ', 'prefix' => 'faq'], function () {
        Route::get('/','IndexController')->name('manager.faq.index');

        Route::get('/create','CreateController')->name('manager.faq.create')->middleware('user-access:admin');
        Route::put('/','StoreController')->name('manager.faq.store')->middleware('user-access:admin');

        Route::get('/{faq}/edit','EditController')->name('manager.faq.edit');
        Route::patch('/{faq}','UpdateController')->name('manager.faq.update')->middleware('user-access:admin');

        Route::delete('/{faq}','DeleteController')->name('manager.faq.delete')->middleware('user-access:admin');
    });
    // Настройки системы
    Route::group(['namespace' => 'Settings', 'prefix' => 'settings', 'middleware' => ['user-access:admin']], function () {
        Route::get('/','EditController')->name('manager.settings.edit');
        Route::patch('/','UpdateController')->name('manager.settings.update');
    });
    // Общая рассылка
    Route::group(['namespace' => 'GenMes', 'prefix' => 'gen_mes', 'middleware' => ['user-access:admin']], function () {
        Route::get('/','CreateController')->name('manager.gen_mes.create');
        Route::put('/create','StoreController')->name('manager.gen_mes.store');
        Route::get('/{gen_mes}/edit','EditController')->name('manager.gen_mes.edit');
        Route::patch('/{gen_mes}/update','UpdateController')->name('manager.gen_mes.update');
        Route::post('/file_delete_by_src','FileDeleteBySrcController');
        Route::delete('/{gen_mes}','DeleteController')->name('manager.gen_mes.delete');
    });
    // Объекты на карте
    Route::group(['namespace' => 'Map', 'prefix' => 'map'], function () {
        Route::get('/','IndexController')->name('manager.map.index');
    });
    // Расходы по гарантии
    Route::group(['namespace' => 'ExpensesGuarantee', 'prefix' => 'expenses_guarantee'], function () {
        Route::get('/','IndexController')->name('manager.expenses_guarantee.index')->can('show_expenses_object');
    });
    // Расходы по объектам: общее для объектов и гарантии
    Route::group(['namespace' => 'ExpensesObject', 'prefix' => 'expenses_object'], function () {
        Route::get('/create/{client_id?}','CreateController')->name('manager.expenses_object.create')->can('edit_expenses_object');
        Route::put('/create','StoreController')->name('manager.expenses_object.store')->can('edit_expenses_object');
        Route::get('/edit/{expense}','EditController')->name('manager.expenses_object.edit')->can('edit_current_expense_object,expense');
        Route::patch('/{expense}','UpdateController')->name('manager.expenses_object.update')->can('edit_current_expense_object,expense');
        Route::patch('/update_estimate/{client}','UpdateEstimateController')->name('manager.expenses_object.update_estimate')->can('edit_expenses_object_estimate');
        Route::delete('/{expense}','DeleteController')->name('manager.expenses_object.delete')->can('edit_current_expense_object,expense');
        Route::get('/export_excel','ExportExcelController')->name('manager.expenses_object.export_excel')->middleware('user-access:admin');
    });
    // Отчёт по материалам
    Route::group(['namespace' => 'ExpensesMatReport', 'prefix' => 'expenses_mat_report'], function () {
        Route::get('/{month_year?}','IndexController')->name('manager.expenses_mat_report.index')->can('show_expenses_mat_report');
        Route::post('/change_month_year','MonthYearController')->name('manager.expenses_mat_report.change_month_year')->can('show_expenses_mat_report');
        Route::get('/year_report/{year}','YearReportController')->name('manager.expenses_mat_report.year_report')->can('show_expenses_mat_report');
        Route::post('/change_year','YearController')->name('manager.expenses_mat_report.change_year')->can('show_expenses_mat_report');
        Route::get('/export_excel/{year}','ExportExcelController')->name('manager.expenses_mat_report.export_excel')->middleware('user-access:admin');
    });
    // Аналитика звонков
    Route::group(['namespace' => 'Calls', 'prefix' => 'calls'], function () {
        Route::get('/{month_year?}','MonthController')->name('manager.calls.month')->can('show_calls');
        Route::post('/change_month','ChangeMonthController')->name('manager.calls.change_month')->can('show_calls');
        Route::get('/edit/{date}','EditController')->name('manager.calls.edit')->can('edit_calls');
        Route::patch('/update','UpdateController')->name('manager.calls.update')->can('edit_calls');
        Route::get('/year/{year}','YearController')->name('manager.calls.year')->can('show_calls');
        Route::post('/change_year','ChangeYearController')->name('manager.calls.change_year')->can('show_calls');
        Route::get('/export_excel/{year}','ExportExcelController')->name('manager.calls.export_excel')->middleware('user-access:admin');
    });
    // Опросы
    Route::group(['namespace' => 'Quiz', 'prefix' => 'quiz'], function () {
        // Результаты опросов и создание опроса
        Route::group(['namespace' => 'Survey', 'prefix' => 'survey'], function () {
            Route::get('/','IndexController')->name('manager.quiz.survey.index')->can('show_quiz');
            Route::get('/show/{survey}','ShowController')->name('manager.quiz.survey.show')->can('show_quiz');
            Route::get('/create/{template_id?}','CreateController')->name('manager.quiz.survey.create')->can('create_survey');
            Route::put('/create','StoreController')->name('manager.quiz.survey.store')->can('create_survey');
            Route::delete('/{survey}','DeleteController')->name('manager.quiz.survey.delete')->can('edit_quiz');
        });
        // Шаблоны опросов
        Route::group(['namespace' => 'Template', 'prefix' => 'template'], function () {
            Route::get('/','IndexController')->name('manager.quiz.template.index')->can('edit_quiz');
            Route::get('/results','ResultsIndexController')->name('manager.quiz.template.results_index')->can('show_quiz');
            Route::get('/create','CreateController')->name('manager.quiz.template.create')->can('edit_quiz');
            Route::get('/{template}/show','ShowController')->name('manager.quiz.template.show')->can('show_quiz');
            Route::get('/{template}/edit','EditController')->name('manager.quiz.template.edit')->can('edit_quiz');
            Route::post('/','StoreController')->name('manager.quiz.template.store')->can('edit_quiz');
            Route::delete('/{template}','DeleteController')->name('manager.quiz.template.delete')->can('edit_quiz');
        });
    });
});
