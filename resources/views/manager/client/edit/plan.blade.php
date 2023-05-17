@extends('manager.client.edit.layout')

@section('pageStyle')
    @parent
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/client/edit/plan.css') }}">
@endsection

@section('action_type_section')
    <div class="callout callout-info">
        <h5>Сроки выполнения работ (по договору)</h5>
        <p class="text_pre_line">{{ empty($client->plan_dates) ? 'Нет информации' : $client->plan_dates }}</p>
    </div>

    <div class="callout callout-warning">
        <h5>План работ</h5>
        <p class="text_pre_line">{{ empty($client->plan_questions) ? 'Нет информации' : $client->plan_questions }}</p>
    </div>

    <div class="callout callout-success">
        <h5>Проверка выполненных работ (этапы)</h5>
        <p class="text_pre_line">{{ empty($client->plan_check) ? 'Нет информации' : $client->plan_check }}</p>
    </div>

    <hr/>
    <h3 class="mb-3">Задачи в работе</h3>
    <button id="addTask" type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal_task_create">
        <i class="fa fa-plus mr-2" aria-hidden="true"></i>Добавить задачу
    </button>

    <div id="tasks_list">
        @if (count($client->active_tasks))
            @foreach ($client->active_tasks as $task)
                <div class="callout {{ (!empty($task->remember[auth()->user()->id])) ? 'callout-danger bg-gradient-danger' : 'callout-info bg-gradient-info' }}">
                    <div class="position-relative">
                        <h5 class="mr-5">{{ $task->name }}</h5>
                        <p>{{ $task->text }}</p>
                        @if (!empty($task->task_files) && count($task->task_files))
                            <div class="row">
                                @foreach ($task->task_files as $file)
                                    @if (mime_content_type(storage_path('app/public/'.$file->src)) === "video/mp4")
                                        <div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2 task_image_block">
                                            <a id="{{ $file->id }}" data-fancybox="gallery{{ $task->id }}" href="{{ url('storage/'.$file->src) }}" data-caption="{{ $task->text }}">
                                                <video class="task_image_item" src="{{ url('storage/'.$file->src) }}" autoplay loop muted playsinline></video>
                                            </a>
                                            @can('edit_task',$task)
                                                <button class="btn btn-warning btn-sm delete_file_button task_file_remove_btn" data-file="{{ url('storage/'.$file->src) }}">
                                                    <i class="fa fa-fw fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    @elseif (str_contains(mime_content_type(storage_path('app/public/'.$file->src)), "image/"))
                                        <div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2 task_image_block">
                                            <a id="{{ $file->id }}" data-fancybox="gallery{{ $task->id }}" href="{{ url('storage/'.$file->src) }}" data-caption="{{ $task->text }}">
                                                <img class="task_image_item" src="{{ url('storage/'.$file->src) }}" alt="...">
                                            </a>
                                            @can('edit_task',$task)
                                                <button class="btn btn-warning btn-sm delete_file_button task_file_remove_btn" data-file="{{ url('storage/'.$file->src) }}">
                                                    <i class="fa fa-fw fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    @else
                                        <div class="col-12 text-truncate mb-2">
                                            @can('edit_task',$task)
                                                <button class="btn btn-warning btn-sm delete_file_button task_file_remove_filename_btn mr-2" data-file="{{ url('storage/'.$file->src) }}">
                                                    <i class="fa fa-fw fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            @endcan
                                            <span data-link="{{ url('storage/'.$file->src) }}" data-name="{{ $file->name }}" class="task_file_download_link">
                                                <i class="fa fa-paperclip mr-2" aria-hidden="true"></i>{{ $file->name }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        <p class="mb-0"><i class="fa fa-bolt mr-2" aria-hidden="true"></i>Ответственный: {{ $task->responsible_name }}</p>
                        <small>Создал: {{ $task->manager_created->name }}, {{ $task->created_str }}</small>
                        <div class="task_edit_btn_block">
                            @if (!empty($task->popover))
                                <button type="button" class="btn bg-transparent p-0 viewed" data-toggle="popover" data-content="{{ $task->popover }}">
                                    <i class="fa fa-lg fa-info-circle" aria-hidden="true"></i>
                                </button>
                            @endif
                            <button type="button" class="btn bg-transparent p-0 ml-1 task_edit_btn" data-id="{{ $task->id }}" data-name="{{ $task->name }}" data-text="{{ $task->text }}" data-responsible="{{ $task->getRawOriginal('responsible') }}" data-remember="{{ !empty($task->remember[auth()->user()->id]) ? 1 : 0 }}" data-closed="{{ !empty($task->closed_at) ? 1 : 0 }}" data-editable="{{ (auth()->user()->can('edit_task',$task)) ? 1 : 0 }}" data-completable="{{ (auth()->user()->can('complete_task',$task)) ? 1 : 0 }}">
                                <i class="fa fa-cog fa-lg" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        @if (count($client->closed_tasks))
            <hr/>
            <h3 class="mb-3">Завершённые задачи</h3>
            @foreach ($client->closed_tasks as $task)
                <div class="callout callout-success">
                    <div class="position-relative">
                        <h5 class="mr-5">{{ $task->name }}</h5>
                        <p>{{ $task->text }}</p>
                        @if (!empty($task->task_files) && count($task->task_files))
                            <div class="row">
                                @foreach ($task->task_files as $file)
                                    @if (mime_content_type(storage_path('app/public/'.$file->src)) === "video/mp4")
                                        <div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2 task_image_block">
                                            <a id="{{ $file->id }}" data-fancybox="gallery{{ $task->id }}" href="{{ url('storage/'.$file->src) }}" data-caption="{{ $task->text }}">
                                                <video class="task_image_item" src="{{ url('storage/'.$file->src) }}" autoplay loop muted playsinline></video>
                                            </a>
                                            @can('edit_task',$task)
                                                <button class="btn btn-warning btn-sm delete_file_button task_file_remove_btn" data-file="{{ url('storage/'.$file->src) }}">
                                                    <i class="fa fa-fw fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    @elseif (str_contains(mime_content_type(storage_path('app/public/'.$file->src)), "image/"))
                                        <div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2 task_image_block">
                                            <a id="{{ $file->id }}" data-fancybox="gallery{{ $task->id }}" href="{{ url('storage/'.$file->src) }}" data-caption="{{ $task->text }}">
                                                <img class="task_image_item" src="{{ url('storage/'.$file->src) }}" alt="...">
                                            </a>
                                            @can('edit_task',$task)
                                                <button class="btn btn-warning btn-sm delete_file_button task_file_remove_btn" data-file="{{ url('storage/'.$file->src) }}">
                                                    <i class="fa fa-fw fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    @else
                                        <div class="col-12 text-truncate mb-2">
                                            @can('edit_task',$task)
                                                <button class="btn btn-warning btn-sm delete_file_button task_file_remove_filename_btn mr-2" data-file="{{ url('storage/'.$file->src) }}">
                                                    <i class="fa fa-fw fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                            @endcan
                                            <span data-link="{{ url('storage/'.$file->src) }}" data-name="{{ $file->name }}" class="task_file_download_link">
                                                <i class="fa fa-paperclip mr-2" aria-hidden="true"></i>{{ $file->name }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        <p class="mb-0"><i class="fa fa-bolt mr-2" aria-hidden="true"></i>Ответственный: {{ $task->responsible_name }}</p>
                        <small>Создал: {{ $task->manager_created->name }}, {{ $task->created_str }}</small>
                        <br/>
                        <small>Завершил: {{ $task->manager_closed->name }}, {{ $task->closed_str }}</small>
                        <div class="task_edit_btn_block">
                            @if (!empty($task->popover))
                                <button type="button" class="btn bg-transparent p-0 viewed" data-toggle="popover" data-content="{{ $task->popover }}">
                                    <i class="fa fa-lg fa-info-circle" aria-hidden="true"></i>
                                </button>
                            @endif
                            <button type="button" class="btn bg-transparent p-0 ml-1 task_edit_btn" data-id="{{ $task->id }}" data-name="{{ $task->name }}" data-text="{{ $task->text }}" data-responsible="{{ $task->getRawOriginal('responsible') }}" data-remember="{{ !empty($task->remember[auth()->user()->id]) ? 1 : 0 }}" data-closed="{{ !empty($task->closed_at) ? 1 : 0 }}" data-editable="{{ (auth()->user()->can('edit_task',$task)) ? 1 : 0 }}" data-completable="{{ (auth()->user()->can('complete_task',$task)) ? 1 : 0 }}">
                                <i class="fa fa-cog fa-lg" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div id="viewed_popover"></div>

    <div id="file_previewer" class="card card-primary maximized-card d-none">
        <div class="card-header long-text">
            <h3 id="file_previewer_title" class="card-title"></h3>
            <div class="card-tools">
                <a id="file_previewer_download_btn" download="" href="" class="btn btn-tool"><i class="fa fa-download"></i></a>
                <button id="file_previewer_close" type="button" class="btn btn-tool">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body file_embed_card_body">
            <iframe id="google_doc_viewer" class="google_doc_viewer file_embed_iframe" src="" data-src=""></iframe>
        </div>
        <!-- /.card-body -->
        <div class="google_doc_viewer_loading overlay dark">
            <i class="fa fa-spinner fa-pulse fa-3x"></i>
        </div>
    </div>

    <div class="modal fade" id="modal_task_create">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div id="modal_create_overlay" class="overlay d-none">
                    <p class="lead"><i class="fa fa-spinner mr-2" aria-hidden="true"></i>Загрузка</p>
                </div>
                <div class="modal-header">
                    <h4 class="modal-title">Новая задача</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="task_create_form" method="post" action="{{ route('manager.client.task.store',$client->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label>Суть задачи</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Суть задачи (кратко)"
                                   value="{{ old('name') }}" required="required">
                            @error('name')
                            <div class="text-danger validation_error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Описание задачи</label>
                            <textarea class="form-control @error('text') is-invalid @enderror" rows="4" name="text" placeholder="Описание задачи (развёрнуто)">{{ old('text') }}</textarea>
                            @error('text')
                            <div class="text-danger validation_error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="create_file_input">Фото, видео или другие файлы</label>
                            <div class="custom-file">
                                <input type="file" id="create_file_input" name="files[]" value="{{ old('files') }}" class="custom-file-input @error('files') is-invalid @enderror" multiple>
                                <label class="custom-file-label" for="create_file_input">Выберите файл(-ы)</label>
                            </div>
                            @error('files')
                            <div class="text-danger validation_error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label>Ответственный</label>
                            <select class="select2 @error('responsible') is-invalid @enderror" name="responsible[]" multiple="multiple" data-placeholder="Выберите ответственного(-ых)" style="width: 100%;">
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->id }}" @if ($errors->has('task_store')) {{ (collect(old('responsible'))->contains($manager->id)) ? 'selected':'' }} @else {{ ($manager->id === auth()->user()->id) ? ' selected' : '' }} @endif>{{ $manager->name }}</option>
                                @endforeach
                            </select>
                            @error('responsible')
                            <div class="text-danger validation_error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input cursor-pointer" id="task_create_form_remember" name="remember" value="1" {{ !empty(old('remember')) ? 'checked' : '' }}>
                                <label class="custom-control-label cursor-pointer" for="task_create_form_remember">Напоминать</label>
                            </div>
                        </div>
                        <input id="task_create_form_submit" type="submit" class="d-none">
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle-o mr-2" aria-hidden="true"></i>Отмена</button>
                    <button type="button" class="btn btn-primary" id="task_create_form_submit_button"><i class="fa fa-check mr-2" aria-hidden="true"></i>Сохранить</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="modal_task_edit">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div id="modal_edit_overlay" class="overlay d-none">
                    <p class="lead"><i class="fa fa-spinner mr-2" aria-hidden="true"></i>Загрузка</p>
                </div>
                <div class="modal-header">
                    <h4 class="modal-title text-truncate" id="modal_task_edit_header">{{ old('name') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="task_edit_form" method="post" action="{{ route('manager.client.task.update',$client->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <input type="hidden" id="modal_task_edit_form_id" name="id" value="{{ old('id') }}"/>
                        <input type="hidden" id="modal_task_edit_form_editable" name="editable" value="{{ old('editable') }}"/>
                        <div class="form-group">
                            <label>Суть задачи</label>
                            <input id="modal_task_edit_form_name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Суть задачи (кратко)"
                                   value="{{ old('name') }}" required="required">
                            @error('name')
                            <div class="text-danger validation_error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Описание задачи</label>
                            <textarea id="modal_task_edit_form_text" class="form-control @error('text') is-invalid @enderror" rows="4" name="text" placeholder="Описание задачи (развёрнуто)">{{ old('text') }}</textarea>
                            @error('text')
                            <div class="text-danger validation_error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="edit_file_input">Добавить файл(-ы)</label>
                            <div class="custom-file">
                                <input type="file" id="edit_file_input" name="files[]" value="{{ old('files') }}" class="custom-file-input @error('files') is-invalid @enderror" multiple>
                                <label class="custom-file-label" for="edit_file_input">Выберите файл(-ы)</label>
                            </div>
                            @error('files')
                            <div class="text-danger validation_error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label>Ответственный</label>
                            <select id="modal_task_edit_form_responsible" class="select2 @error('responsible') is-invalid @enderror" name="responsible[]" multiple="multiple" data-placeholder="Выберите ответственного(-ых)" style="width: 100%;">
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->id }}" {{ (collect(old('responsible'))->contains($manager->id)) ? 'selected':'' }}>{{ $manager->name }}</option>
                                @endforeach
                            </select>
                            @error('responsible')
                            <div class="text-danger validation_error">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input cursor-pointer" id="task_edit_form_remember" name="remember" value="1" {{ !empty(old('remember')) ? 'checked' : '' }}>
                                <label class="custom-control-label cursor-pointer" for="task_edit_form_remember">Напоминать</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input cursor-pointer" id="task_edit_form_closed" name="closed" value="1" {{ !empty(old('closed')) ? 'checked' : '' }}>
                                <label class="custom-control-label cursor-pointer" for="task_edit_form_closed">Завершена</label>
                            </div>
                        </div>
                        <input id="task_edit_form_submit" type="submit" class="d-none">
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    @can('delete_task')
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle-o mr-2" aria-hidden="true"></i>Отм<span class="d-none d-sm-inline">ена</span></button>
                        <button type="button" class="btn btn-danger task_delete_btn"><i class="fa fa-trash-o mr-2" aria-hidden="true"></i>Удал<span class="d-none d-sm-inline">ить</span></button>
                        <button type="button" class="btn btn-primary" id="task_edit_form_submit_button"><i class="fa fa-check mr-2" aria-hidden="true"></i>Сохр<span class="d-none d-sm-inline">анить</span></button>
                    @else
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle-o mr-2" aria-hidden="true"></i>Отмена</button>
                        <button type="button" class="btn btn-primary" id="task_edit_form_submit_button"><i class="fa fa-check mr-2" aria-hidden="true"></i>Сохранить</button>
                    @endcan
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <form id="task_delete_form" method="post" action="{{ route('manager.client.task.delete',$client->id) }}">
        @csrf
        @method('delete')
        <input type="hidden" id="task_delete_id" name="id" value=""/>
    </form>

@endsection

@section('pageScript')
    @parent
    <script src="{{ assetVersioned('dist/js/pages/manager/client/edit/plan.js') }}"></script>
    @if($errors->has('task_store'))
        <script>$('#modal_task_create').modal('show');</script>
    @endif
    @if($errors->has('task_update'))
        <script>
            $('#modal_task_edit').modal('show');
            if (parseInt($('#modal_task_edit_form_editable').val(),10) === 0) {
                $('#modal_task_edit_form_name, #modal_task_edit_form_text').prop('readonly',true);
                $('#modal_task_edit_form_responsible,#edit_file_input').prop('disabled',true);
            }
        </script>
    @endif
@endsection
