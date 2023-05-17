@extends('manager.client.edit.layout')

@section('pageStyle')
    @parent
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/client/edit/tech_doc.css') }}">
@endsection

@section('action_type_section')
    <div class="callout callout-info">
        <p>Этой кнопкой можно загрузить документы в кабинет заказчика.</p>
        <div>
            <button id="addFile" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal_create">
                <i class="fa fa-plus mr-2" aria-hidden="true"></i>Загрузить файл
            </button>
        </div>
    </div>
    <hr/>
    @if (count($client->tech_docs))
        <div id="tech_doc_list">
            @foreach ($client->tech_docs as $tech_doc)
                <div class="card card-primary">
                    <div class="card-header long-text">
                        <h3 class="card-title">{{ $tech_doc->name }}</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                            <a download="{{ $tech_doc->name }}.pdf" href="{{ url('storage/'.$tech_doc->file) }}" class="btn btn-tool"><i class="fa fa-download"></i></a>
                            @if (!empty($tech_doc->popover))
                                <button type="button" class="btn btn-tool viewed" data-toggle="popover" data-content="{{ $tech_doc->popover }}">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                </button>
                            @endif
                            @can('edit_tech_doc',$tech_doc)
                            <button type="button" class="btn btn-tool edit_file" data-id="{{ $tech_doc->id }}" data-name="{{ $tech_doc->name }}" data-comment="{{ $tech_doc->comment }}">
                                <i class="fa fa-pencil"></i>
                            </button>
                            @endcan
                            @can('delete_tech_doc')
                                <form method="post" action="{{ route('manager.client.tech_doc.delete',[$client->id,$tech_doc->id]) }}" class="d-inline">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-tool confirm_tech_doc_delete"><i class="fa fa-trash"></i></button>
                                </form>
                            @endcan
                            <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                <i class="fa fa-expand"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body tech_doc_info">
                        <p class="tech_doc_comment"><b>Комментарий:</b> {!! $tech_doc->comment_urlified !!}</p>
                        <p class="mb-0"><b>Загрузил:</b> {{ !empty($tech_doc->manager) ? $tech_doc->manager->name.', '.$tech_doc->created_str : 'Заказчик, '.$tech_doc->created_str }}</p>
                    </div>
                    <div class="card-body file_embed_card_body">
                        <iframe class="google_doc_viewer file_embed_iframe" src="" data-src="{{ url('storage/'.$tech_doc->file) }}"></iframe>
                    </div>
                    <!-- /.card-body -->
                    <div class="google_doc_viewer_loading overlay dark">
                        <i class="fa fa-spinner fa-pulse fa-3x"></i>
                    </div>
                </div>
            @endforeach
            <div id="viewed_popover"></div>
        </div>
    @else
        <p class="text-center p-0 m-0">Загруженных документов нет</p>
    @endif

    <div class="modal fade" id="modal_create">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div id="modal_create_overlay" class="overlay d-none">
                    <p class="lead"><i class="fa fa-spinner mr-2" aria-hidden="true"></i>Файл загружается</p>
                </div>
                <div class="modal-header">
                    <h4 class="modal-title">Новый документ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="modal_create_form" method="post" action="{{ route('manager.client.tech_doc.store',$client->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label>Название документа</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Название документа"
                                   value="{{ old('name') }}" required="required">
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Комментарий</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" rows="3" name="comment" placeholder="Ваш комментарий к документу">{{ old('comment') }}</textarea>
                            @error('comment')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="create_file_input">Файл</label>
                            <div class="custom-file">
                                <input type="file" name="file" value="{{ old('file') }}" class="custom-file-input @error('file') is-invalid @enderror" id="create_file_input" accept="application/pdf" required="required">
                                <label class="custom-file-label" for="create_file_input">PDF до 20 Мб</label>
                            </div>
                            @error('file')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <p class="mt-3 mb-0"><i>Примечание</i>. Вы можете уменьшить файлы большого размера <a href="https://www.ilovepdf.com/ru/compress_pdf" target="_blank">здесь</a>.</p>
                        </div>
                        <input id="modal_create_form_submit" type="submit" class="d-none">
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle-o mr-2" aria-hidden="true"></i>Отмена</button>
                    <button type="button" class="btn btn-primary" id="modal_create_form_submit_button"><i class="fa fa-check mr-2" aria-hidden="true"></i>Загрузить</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <div class="modal fade" id="modal_edit">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_edit_title"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('manager.client.tech_doc.update',$client->id) }}">
                        @csrf
                        @method('patch')
                        <input id="edit_form_id" type="hidden" name="edit_id" value="">
                        @error('edit_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-group">
                            <label>Название документа</label>
                            <input id="edit_form_name" type="text" class="form-control @error('edit_name') is-invalid @enderror" name="edit_name" placeholder="Название документа ..."
                                   value="{{ old('edit_name') }}" required="required">
                            @error('edit_name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Комментарий</label>
                            <textarea id="edit_form_comment" class="form-control @error('edit_comment') is-invalid @enderror" rows="3" name="edit_comment" placeholder="Ваш комментарий к документу ...">{{ old('edit_comment') }}</textarea>
                            @error('edit_comment')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <input id="modal_edit_form_submit" type="submit" class="d-none">
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle-o mr-2" aria-hidden="true"></i>Отмена</button>
                    <button type="button" class="btn btn-primary" id="modal_edit_form_submit_button"><i class="fa fa-check mr-2" aria-hidden="true"></i>Сохранить</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('pageScript')
    @parent
    <script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script src="{{ assetVersioned('dist/js/pages/manager/client/edit/tech_doc.js') }}"></script>
    @if($errors->has('tech_doc_store'))
        <script>$('#modal_create').modal('show');</script>
    @endif
    @if($errors->has('tech_doc_update'))
        <script>$('#modal_edit').modal('show');</script>
    @endif
@endsection
