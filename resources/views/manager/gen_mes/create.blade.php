@extends('manager.layouts.main')

@section('pageName')Общая рассылка@endsection

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
                <h1 class="mt-0">Общая рассылка</h1>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <form action="{{ route('manager.gen_mes.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <input type="file" id="add_message_files" name="add_message_files[]" multiple class="d-none">
                    <div class="card card-primary">
                        <div class="card-body">
                            <p class="mb-2"><b>Кому:</b></p>
                            <div class="form-group">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="to_in_process_1" name="to_in_process_1" value="1" @if(old('to_in_process_1')) checked @endif>
                                    <label for="to_in_process_1" class="font-weight-normal">
                                        <span class="ml-1">Работа начата</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="to_in_process_0" name="to_in_process_0" value="1" @if(old('to_in_process_0')) checked @endif>
                                    <label for="to_in_process_0" class="font-weight-normal">
                                        <span class="ml-1">Работа не начата</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox" id="to_ob_status_2" name="to_ob_status_2" value="1" @if(old('to_ob_status_2')) checked @endif>
                                    <label for="to_ob_status_2" class="font-weight-normal">
                                        <span class="ml-1">Объекты на гарантии</span>
                                    </label>
                                </div>
                            </div>
                            <hr/>
                            <div id="files_preview_block"></div>
                            <div class="form-group">
                                <label for="ext">Сообщение:</label>
                                <textarea id="text" class="form-control @error('text') is-invalid @enderror" name="text" rows="4" placeholder="Сообщение" autofocus>{{ old('text') }}</textarea>
                                @error('text')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="pull-right">
                                <button id="add_file" class="btn bg-lightblue">
                                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                                </button>
                                <button type="submit" class="btn btn-primary" onclick="$('#message_sending').removeClass('d-none');">
                                    <i class="fa fa-paper-plane-o mr-2" aria-hidden="true"></i>Отправить
                                </button>
                            </div>
                        </div>
                        <div id="message_sending" class="overlay dark d-none">
                            <i class="fa fa-spinner fa-pulse fa-3x"></i>
                        </div>
                    </div>
                </form>

                <h3 class="mt-5">Отправленные сообщения</h3>
                @if (!empty($sent) && count($sent))
                    @foreach ($sent as $mes)
                        <div class="card">
                            <div class="card-body">
                                <div class="btn_block">
                                    <form action="{{ route('manager.gen_mes.delete', $mes['id']) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs px-2 confirm_message_delete"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                    </form>
                                    <a class="btn btn-primary btn-xs px-2" href="{{ route('manager.gen_mes.edit', $mes['id']) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                </div>
                                <div class="mr-5">
                                    <small class="text-muted">
                                        {{ $mes->author_extended }},
                                        {{ $mes->created_text }}
                                    </small>
                                </div>
                                <hr/>
                                <b>Кому:</b>
                                <ul class="m-0 pl-3">
                                    @if (!empty($mes->to_in_process_1))
                                        <li>Работа начата</li>
                                    @endif
                                    @if (!empty($mes->to_in_process_0))
                                        <li>Работа не начата</li>
                                    @endif
                                    @if (!empty($mes->to_ob_status_2))
                                        <li>Объекты на гарантии</li>
                                    @endif
                                </ul>
                                <hr/>
                                @if (!empty($mes->files_list) && count($mes->files_list))
                                    <div class="row">
                                        @foreach ($mes->files_list as $file)
                                            @if ($file->type === "video/mp4")
                                                <div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2 message_image_block">
                                                    <a id="{{ $file->id }}" data-fancybox="gallery" href="{{ url('storage/'.$file->src) }}" data-caption="{{ $mes->text }}">
                                                        <video class="message_image_item" src="{{ url('storage/'.$file->src) }}" autoplay loop muted playsinline width="{{ $file->width }}" height="{{ $file->height }}"></video>
                                                    </a>
                                                </div>
                                            @elseif ($file->type === "image")
                                                <div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2 message_image_block">
                                                    <a id="{{ $file->id }}" data-fancybox="gallery" href="{{ url('storage/'.$file->src) }}" data-caption="{{ $mes->text }}">
                                                        <img class="message_image_item" src="{{ url('storage/'.$file->src) }}" alt="" width="{{ $file->width }}" height="{{ $file->height }}">
                                                    </a>
                                                </div>
                                            @elseif (!empty($file->preview))
                                                <div class="col-6 col-xs-6 col-sm-4 col-md-3 col-lg-2 message_image_block message_file_download_link" data-link="{{ url('storage/'.$file->src) }}" data-name="{{ $file->name }}">
                                                    <img class="message_image_item" src="{{ url('storage/'.$file->preview) }}" alt="{{ $file->name }}" width="{{ $file->width }}" height="{{ $file->height }}">
                                                </div>
                                            @else
                                                <div class="col-12 text-truncate">
                                                <span data-link="{{ url('storage/'.$file->src) }}" data-name="{{ $file->name }}" class="message_file_download_link">
                                                    <i class="fa fa-paperclip mr-2" aria-hidden="true"></i>{{ $file->name }}
                                                </span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                                @if (!empty($mes->text))
                                    <p class="mb-1"><b>Сообщение:</b></p>
                                    <div class="chat_message_content">{!! $mes->text_urlified !!}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="card">
                        <div class="card-body">
                            Сообщений, отправленных ранее при помощи общей рассылки, не обнаружено.
                        </div>
                    </div>
                @endif


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
