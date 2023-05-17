<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'App\Http\Controllers\Api'], function () {
    Route::get('/checkChatUpdate', 'CheckChatUpdateController'); // Проверка обновления чата

    Route::group(['namespace' => 'Client', 'prefix' => 'client'], function () {
        Route::get('/checkNewEvents', 'CheckNewEventsController'); // Новые события для одного объекта
    });
    Route::group(['namespace' => 'Manager', 'prefix' => 'manager'], function () {
        Route::get('/checkNewEventsClient', 'CheckNewEventsClientController'); // Новые события для одного объекта
        Route::get('/checkNewEventsList', 'CheckNewEventsInListController'); // Новые события для всех объектов в списке
    });

    Route::post('/storeChatDraft', 'ChatDraftStoreController'); // Сохранение черновика чата
});
