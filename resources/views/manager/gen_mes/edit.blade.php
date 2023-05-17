@extends('manager.layouts.main')

@section('pageName')Редактирование сообщения@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/common/chat_add_files.css') }}">
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/gen_mes/edit.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">Редактирование сообщения</h1>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('manager.gen_mes.create') }}">Общая рассылка</a></li>
                    <li class="breadcrumb-item active">Редактирование сообщения</li>
                </ol>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-body">
                        <b>Кому:</b>
                        <ul class="m-0 pl-3">
                            @if (!empty($gen_mes->to_in_process_1))
                                <li>Работа начата</li>
                            @endif
                            @if (!empty($gen_mes->to_in_process_0))
                                <li>Работа не начата</li>
                            @endif
                            @if (!empty($gen_mes->to_ob_status_2))
                                <li>Объекты на гарантии</li>
                            @endif
                        </ul>
                    </div>
                </div>
                @if (!empty($gen_mes->files_list) && count($gen_mes->files_list))
                    <div class="card card-primary">
                        <div class="card-body">
                            <p class="mb-2"><b>Прикреплённые файлы:</b></p>
                            <div class="row">
                                @foreach ($gen_mes->files_list as $file)
                                    @if ($file->type === "video/mp4")
                                        <div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2 message_image_block">
                                            <a id="{{ $file->id }}" data-fancybox="gallery" href="{{ url('storage/'.$file->src) }}" data-caption="{{ $gen_mes->text }}">
                                                <video class="message_image_item" src="{{ url('storage/'.$file->src) }}" autoplay loop muted playsinline width="{{ $file->width }}" height="{{ $file->height }}"></video>
                                            </a>
                                            <button class="btn btn-warning btn-sm delete_file_button file_remove_btn" data-file="{{ url('storage/'.$file->src) }}" data-gen_mes="{{ $gen_mes->id }}">
                                                <i class="fa fa-fw fa-trash-o" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    @elseif ($file->type === "image")
                                        <div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2 message_image_block">
                                            <a id="{{ $file->id }}" data-fancybox="gallery" href="{{ url('storage/'.$file->src) }}" data-caption="{{ $gen_mes->text }}">
                                                <img class="message_image_item" src="{{ url('storage/'.$file->src) }}" alt="" width="{{ $file->width }}" height="{{ $file->height }}">
                                            </a>
                                            <button class="btn btn-warning btn-sm delete_file_button file_remove_btn" data-file="{{ url('storage/'.$file->src) }}" data-gen_mes="{{ $gen_mes->id }}">
                                                <i class="fa fa-fw fa-trash-o" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    @elseif (!empty($file->preview))
                                        <div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2 message_image_block message_file_download_link" data-link="{{ url('storage/'.$file->src) }}" data-name="{{ $file->name }}">
                                            <img class="message_image_item" src="{{ url('storage/'.$file->preview) }}" alt="{{ $file->name }}" width="{{ $file->width }}" height="{{ $file->height }}">
                                            <button class="btn btn-warning btn-sm delete_file_button file_remove_btn" data-file="{{ url('storage/'.$file->src) }}" data-gen_mes="{{ $gen_mes->id }}">
                                                <i class="fa fa-fw fa-trash-o" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    @else
                                        <div class="col-12 text-truncate">
                                            <button class="btn btn-warning btn-sm delete_file_button file_remove_filename_btn mr-2" data-file="{{ url('storage/'.$file->src) }}" data-gen_mes="{{ $gen_mes->id }}">
                                                <i class="fa fa-fw fa-trash-o" aria-hidden="true"></i>
                                            </button>
                                            <span data-link="{{ url('storage/'.$file->src) }}" data-name="{{ $file->name }}" class="message_file_download_link">
                                                <i class="fa fa-paperclip mr-2" aria-hidden="true"></i>{{ $file->name }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <form action="{{ route('manager.gen_mes.update', $gen_mes->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <input type="file" id="add_message_files" name="add_message_files[]" multiple class="d-none">
                    <div class="card card-primary">
                        <div class="card-body">
                            <div id="files_preview_block"></div>
                            <div class="form-group">
                                <label for="ext">Сообщение:</label>
                                <textarea id="text" class="form-control @error('text') is-invalid @enderror" name="text" rows="4" placeholder="Сообщение" autofocus>{{ old('text',$gen_mes->text) }}</textarea>
                                @error('text')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="pull-left text-muted pt-2 d-none d-xl-block">
                                <small><i class="fa fa-info-circle mr-1" aria-hidden="true"></i>Сообщение будет изменено в чатах всех заказчиков.</small>
                            </div>
                            <div class="pull-right">
                                <button id="add_file" class="btn bg-lightblue">
                                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                                </button>
                                <button type="submit" class="btn btn-primary" onclick="$('#message_sending').removeClass('d-none');">
                                    <i class="fa fa-check mr-2" aria-hidden="true"></i>Сохранить
                                </button>
                            </div>
                        </div>
                        <div id="message_sending" class="overlay dark d-none">
                            <i class="fa fa-spinner fa-pulse fa-3x"></i>
                        </div>
                    </div>
                </form>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
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
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/common/chat_add_files.js') }}"></script>
    <script src="{{ assetVersioned('dist/js/pages/manager/gen_mes/gen_mes.js') }}"></script>
@endsection
