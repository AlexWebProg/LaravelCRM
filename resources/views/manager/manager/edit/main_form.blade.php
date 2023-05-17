@extends('manager.manager.edit.layout')

@section('pageStyle')
    @parent
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/client/edit/main_form.css') }}">
@endsection

@section('action_type_section')
    <form id="main_form" action="{{ route('manager.manager.update', $manager->id) }}" method="post">
        @csrf
        @method('patch')
        <div class="row">
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Данные {{ empty($manager->is_admin) ? 'сотрудника' : 'руководителя' }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fa fa-minus" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Имя</label>
                            <input type="text" class="form-control" name="name" placeholder="Имя {{ empty($manager->is_admin) ? 'сотрудника' : 'руководителя' }}"
                                   value="{{ old('name', $manager->name) }}" required="required" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Телефон</label>
                            <input type="text" class="form-control" name="phone" placeholder="Телефон"
                                   value="{{ old('phone', $manager->phone) }}" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                            @error('phone')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control" name="email" placeholder="Email" required="required"
                                   value="{{ old('email', $manager->email) }}" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Тип пользователя</label>
                            <select class="form-control" name="is_admin" @if (empty(auth()->user()->is_admin))disabled="disabled"@endif>
                                <option value="0"
                                    {{ 0 == old('is_admin', $manager->is_admin) ? ' selected' : '' }}>
                                    Сотрудник
                                </option>
                                <option value="1"
                                    {{ 1 == old('is_admin', $manager->is_admin) ? ' selected' : '' }}>
                                    Руководитель
                                </option>
                            </select>
                            @error('is_admin')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <input type="hidden" name="id" value="{{ $manager->id }}"/>
                        <input id="main_form_submit" type="submit" class="d-none">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                @if (auth()->user()->is_admin && !$manager->is_admin)
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Разрешения</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">

                            <p class="text-bold"><i class="fa fa-star-o fa-fw mr-2" aria-hidden="true"></i>Объекты</p>
                            <div class="form-group">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="show_ob_status_2" name="show_ob_status_2" value="1" @if(old('show_ob_status_2', (old('_token') ? false : ($manager->show_ob_status_2 ?? false)))) checked @endif>
                                    <label for="show_ob_status_2" class="font-weight-normal">
                                        <span class="ml-1">Просмотр объектов на гарантии</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Просмотр объектов на гарантии"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="show_ob_status_3" name="show_ob_status_3" value="1" @if(old('show_ob_status_3', (old('_token') ? false : ($manager->show_ob_status_3 ?? false)))) checked @endif>
                                    <label for="show_ob_status_3" class="font-weight-normal">
                                        <span class="ml-1">Просмотр завершённых объектов</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Просмотр завершённых объектов"></i>
                                </div>
                            </div>
                            <hr/>

                            <p class="text-bold"><i class="fa fa-video-camera fa-fw mr-2" aria-hidden="true"></i>Веб-камера</p>
                            <div class="form-group">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="show_webcam" name="show_webcam" value="1" @if(old('show_webcam', (old('_token') ? false : ($manager->show_webcam ?? false)))) checked @endif>
                                    <label for="show_webcam" class="font-weight-normal">
                                        <span class="ml-1">Просмотр</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Доступ к разделам веб-камер объектов"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="edit_webcam" name="edit_webcam" value="1" @if(old('edit_webcam', (old('_token') ? false : ($manager->edit_webcam ?? false)))) checked @endif>
                                    <label for="edit_webcam" class="font-weight-normal">
                                        <span class="ml-1">Редактирование</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Указание ссылки на веб-камеру объекта"></i>
                                </div>
                            </div>
                            <hr/>

                            <p class="text-bold"><i class="fa fa-camera fa-fw mr-2" aria-hidden="true"></i>Фото</p>
                            <div class="form-group">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="show_photo" name="show_photo" value="1" @if(old('show_photo', (old('_token') ? false : ($manager->show_photo ?? false)))) checked @endif>
                                    <label for="show_photo" class="font-weight-normal">
                                        <span class="ml-1">Просмотр и загрузка</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Просмотр и загрузка фото и видео в карточках объектов, редактирование и удаление своих фото и видео в течение 10 минут после загрузки"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="edit_photo" name="edit_photo" value="1" @if(old('edit_photo', (old('_token') ? false : ($manager->edit_photo ?? false)))) checked @endif>
                                    <label for="edit_photo" class="font-weight-normal">
                                        <span class="ml-1">Удаление любых фото</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Удаление любых фото в любое время, а не только своих, в течение 10 минут после загрузки"></i>
                                </div>
                            </div>
                            <hr/>

                            <p class="text-bold"><i class="fa fa-info-circle fa-fw mr-2" aria-hidden="true"></i>План работ</p>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="show_plan" name="show_plan" value="1" @if(old('show_plan', (old('_token') ? false : ($manager->show_plan ?? false)))) checked @endif>
                                    <label for="show_plan" class="font-weight-normal">
                                        <span class="ml-1">Просмотр и создание задач</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Просмотр планов работ в карточках объектов, создание задач и управление своими задачами"></i>
                                </div>
                            </div>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="edit_task" name="edit_task" value="1" @if(old('edit_task', (old('_token') ? false : ($manager->edit_task ?? false)))) checked @endif>
                                    <label for="edit_task" class="font-weight-normal">
                                        <span class="ml-1">Редактирование любых задач в плане работ</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Редактирование любых задач, а не только своих"></i>
                                </div>
                            </div>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="delete_task" name="delete_task" value="1" @if(old('delete_task', (old('_token') ? false : ($manager->delete_task ?? false)))) checked @endif>
                                    <label for="delete_task" class="font-weight-normal">
                                        <span class="ml-1">Удаление задач в плане работ</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Удаление любых задач в плане работ"></i>
                                </div>
                            </div>
                            <hr/>

                            <p class="text-bold"><i class="fa fa-file-text-o fa-fw mr-2" aria-hidden="true"></i>Смета</p>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="show_estimate" name="show_estimate" value="1" @if(old('show_estimate', (old('_token') ? false : ($manager->show_estimate ?? false)))) checked @endif>
                                    <label for="show_estimate" class="font-weight-normal">
                                        <span class="ml-1">Просмотр и загрузка</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Просмотр и загрузка смет"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="edit_estimate" name="edit_estimate" value="1" @if(old('edit_estimate', (old('_token') ? false : ($manager->edit_estimate ?? false)))) checked @endif>
                                    <label for="edit_estimate" class="font-weight-normal">
                                        <span class="ml-1">Удаление</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Удаление любых смет"></i>
                                </div>
                            </div>
                            <hr/>

                            <p class="text-bold"><i class="fa fa-file-text fa-fw mr-2" aria-hidden="true"></i>Смета для мастера</p>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="show_master_estimate" name="show_master_estimate" value="1" @if(old('show_master_estimate', (old('_token') ? false : ($manager->show_master_estimate ?? false)))) checked @endif>
                                    <label for="show_master_estimate" class="font-weight-normal">
                                        <span class="ml-1">Просмотр и загрузка</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Просмотр и загрузка смет для мастера"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="edit_master_estimate" name="edit_master_estimate" value="1" @if(old('edit_master_estimate', (old('_token') ? false : ($manager->edit_master_estimate ?? false)))) checked @endif>
                                    <label for="edit_master_estimate" class="font-weight-normal">
                                        <span class="ml-1">Удаление</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Удаление любых смет для мастера"></i>
                                </div>
                            </div>
                            <hr/>

                            <p class="text-bold"><i class="fa fa-comments-o fa-fw mr-2" aria-hidden="true"></i>Чат</p>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="show_chat" name="show_chat" value="1" @if(old('show_chat', (old('_token') ? false : ($manager->show_chat ?? false)))) checked @endif>
                                    <label for="show_chat" class="font-weight-normal">
                                        <span class="ml-1">Просмотр и отправка сообщений</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Просмотр и отправка сообщений, редактирование и удаление своих сообщений до прочтения заказчиком и в течение 5 минут после"></i>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="edit_chat" name="edit_chat" value="1" @if(old('edit_chat', (old('_token') ? false : ($manager->edit_chat ?? false)))) checked @endif>
                                    <label for="edit_chat" class="font-weight-normal">
                                        <span class="ml-1">Удаление и редактирование любых наших сообщений</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Удаление и редактирование любых наших сообщений, а не только своих, в чате до прочтения заказчиком и в течение 5 минут после"></i>
                                </div>
                            </div>
                            <hr/>

                            <p class="text-bold"><i class="fa fa-file-pdf-o fa-fw mr-2" aria-hidden="true"></i>Техническая документация</p>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="show_tech_doc" name="show_tech_doc" value="1" @if(old('show_tech_doc', (old('_token') ? false : ($manager->show_tech_doc ?? false)))) checked @endif>
                                    <label for="show_tech_doc" class="font-weight-normal">
                                        <span class="ml-1">Просмотр и загрузка документов</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Просмотр, загрузка и редактирование своих технических документов"></i>
                                </div>
                            </div>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="edit_tech_doc" name="edit_tech_doc" value="1" @if(old('edit_tech_doc', (old('_token') ? false : ($manager->edit_tech_doc ?? false)))) checked @endif>
                                    <label for="edit_tech_doc" class="font-weight-normal">
                                        <span class="ml-1">Редактирование любых технических документов</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Редактирование любых, а не только загруженных собой, технических документов (названий и комментариев)"></i>
                                </div>
                            </div>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="delete_tech_doc" name="delete_tech_doc" value="1" @if(old('delete_tech_doc', (old('_token') ? false : ($manager->delete_tech_doc ?? false)))) checked @endif>
                                    <label for="delete_tech_doc" class="font-weight-normal">
                                        <span class="ml-1">Удаление технических документов</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Удаление любых технических документов"></i>
                                </div>
                            </div>
                            <hr/>

                            <p class="text-bold"><i class="fa fa-wrench fa-fw mr-2" aria-hidden="true"></i>Расходы по объектам и гарантии</p>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="show_expenses_object" name="show_expenses_object" value="1" @if(old('show_expenses_object', (old('_token') ? false : ($manager->show_expenses_object ?? false)))) checked @endif>
                                    <label for="show_expenses_object" class="font-weight-normal">
                                        <span class="ml-1">Просмотр</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Просмотр расходов по объектам и гарантии"></i>
                                </div>
                            </div>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="edit_expenses_object" name="edit_expenses_object" value="1" @if(old('edit_expenses_object', (old('_token') ? false : ($manager->edit_expenses_object ?? false)))) checked @endif>
                                    <label for="edit_expenses_object" class="font-weight-normal">
                                        <span class="ml-1">Редактирование своих расходов</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Редактирование только своих расходов по объектам и гарантии"></i>
                                </div>
                            </div>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="edit_expenses_object_all" name="edit_expenses_object_all" value="1" @if(old('edit_expenses_object_all', (old('_token') ? false : ($manager->edit_expenses_object_all ?? false)))) checked @endif>
                                    <label for="edit_expenses_object_all" class="font-weight-normal">
                                        <span class="ml-1">Редактирование всех расходов</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Редактирование всех расходов по объектам и гарантии, как своих, так и других сотрудников"></i>
                                </div>
                            </div>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="edit_expenses_object_estimate" name="edit_expenses_object_estimate" value="1" @if(old('edit_expenses_object_estimate', (old('_token') ? false : ($manager->edit_expenses_object_estimate ?? false)))) checked @endif>
                                    <label for="edit_expenses_object_estimate" class="font-weight-normal">
                                        <span class="ml-1">Редактирование сумм по смете</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Редактирование сумм, заложенных для объекта по смете"></i>
                                </div>
                            </div>
                            <hr/>

                            <p class="text-bold"><i class="fa fa-balance-scale fa-fw mr-2" aria-hidden="true"></i>Отчёт по расходам</p>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="show_expenses_personal" name="show_expenses_personal" value="1" @if(old('show_expenses_personal', (old('_token') ? false : ($manager->show_expenses_personal ?? false)))) checked @endif>
                                    <label for="show_expenses_personal" class="font-weight-normal">
                                        <span class="ml-1">Просмотр и ред. отчёта по своим прих/расх</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Просмотр и редактирование отчёта по своим приходам и расходам"></i>
                                </div>
                            </div>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="create_expense_income" name="create_expense_income" value="1" @if(old('create_expense_income', (old('_token') ? false : ($manager->create_expense_income ?? false)))) checked @endif>
                                    <label for="create_expense_income" class="font-weight-normal">
                                        <span class="ml-1">Создание приходов</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Создание приходов в своём отчёте. Все приходы суммируются в столбце Получено отчёта по материалам"></i>
                                </div>
                            </div>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="show_all_expenses_personal" name="show_all_expenses_personal" value="1" @if(old('show_all_expenses_personal', (old('_token') ? false : ($manager->show_all_expenses_personal ?? false)))) checked @endif>
                                    <label for="show_all_expenses_personal" class="font-weight-normal">
                                        <span class="ml-1">Просмотр отчётов всех сотр.</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Просмотр отчётов по приходам и расходам всех сотрудников"></i>
                                </div>
                            </div>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="edit_all_expenses_personal" name="edit_all_expenses_personal" value="1" @if(old('edit_all_expenses_personal', (old('_token') ? false : ($manager->edit_all_expenses_personal ?? false)))) checked @endif>
                                    <label for="edit_all_expenses_personal" class="font-weight-normal">
                                        <span class="ml-1">Редактирование отчётов всех сотр.</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Редактирование отчётов по приходам и расходам всех сотрудников"></i>
                                </div>
                            </div>
                            <hr/>

                            <p class="text-bold"><i class="fa fa-calendar fa-fw mr-2" aria-hidden="true"></i>Отчёт по материалам</p>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="show_expenses_mat_report" name="show_expenses_mat_report" value="1" @if(old('show_expenses_mat_report', (old('_token') ? false : ($manager->show_expenses_mat_report ?? false)))) checked @endif>
                                    <label for="show_expenses_mat_report" class="font-weight-normal">
                                        <span class="ml-1">Доступен отчёт по материалам</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Доступен отчёт по материалам"></i>
                                </div>
                            </div>
                            <hr/>

                            <p class="text-bold"><i class="fa fa-phone-square fa-fw mr-2" aria-hidden="true"></i>Аналитика звонков</p>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="show_calls" name="show_calls" value="1" @if(old('show_calls', (old('_token') ? false : ($manager->show_calls ?? false)))) checked @endif>
                                    <label for="show_calls" class="font-weight-normal">
                                        <span class="ml-1">Просмотр</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Доступен просмотр аналитики звонков"></i>
                                </div>
                            </div>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="edit_calls" name="edit_calls" value="1" @if(old('edit_calls', (old('_token') ? false : ($manager->edit_calls ?? false)))) checked @endif>
                                    <label for="edit_calls" class="font-weight-normal">
                                        <span class="ml-1">Редактирование</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Доступно редактирование аналитики звонков"></i>
                                </div>
                            </div>
                            <hr/>

                            <p class="text-bold"><i class="fa fa-pie-chart fa-fw mr-2" aria-hidden="true"></i>Опросы</p>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="show_quiz" name="show_quiz" value="1" @if(old('show_quiz', (old('_token') ? false : ($manager->show_quiz ?? false)))) checked @endif>
                                    <label for="show_quiz" class="font-weight-normal">
                                        <span class="ml-1">Просмотр результатов</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Доступен просмотр результатов опросов"></i>
                                </div>
                            </div>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="create_survey" name="create_survey" value="1" @if(old('create_survey', (old('_token') ? false : ($manager->create_survey ?? false)))) checked @endif>
                                    <label for="create_survey" class="font-weight-normal">
                                        <span class="ml-1">Проведение опросов</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Доступно проведение опросов"></i>
                                </div>
                            </div>
                            <div class="form-group text-truncate">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="edit_quiz" name="edit_quiz" value="1" @if(old('edit_quiz', (old('_token') ? false : ($manager->edit_quiz ?? false)))) checked @endif>
                                    <label for="edit_quiz" class="font-weight-normal">
                                        <span class="ml-1">Редактирование шаблонов</span>
                                    </label>
                                    <i class="fa fa-question-circle-o ml-1" aria-hidden="true" data-toggle="popover" data-content="Доступно создание, редактирование и удаление шаблонов"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif
                @if (auth()->user()->is_admin && auth()->user()->id !== $manager->id)
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Функции</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-danger pull-right confirm_user_delete"><i class="fa fa-trash mr-2"></i> Удалить {{ empty($manager->is_admin) ? 'сотрудника' : 'руководителя' }}</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </form>
    @if (auth()->user()->is_admin && auth()->user()->id !== $manager->id)
        <form action="{{ route('manager.manager.delete', $manager->id) }}" method="post" id="delete_manager_form" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endif
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/manager/manager/edit.js') }}"></script>
@endsection
