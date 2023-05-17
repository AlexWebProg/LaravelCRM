@extends('manager.client.edit.layout')

@section('navbarItem')
    @if (!empty(auth()->user()->is_admin))
        <li class="nav-item">
            <button class="nav-link btn bg-transparent pl-0 pl-lg-2" onclick="document.title = 'Чат {{ $client->address }}';window.print();">
                <i class="fa fa-print" aria-hidden="true"></i>
            </button>
        </li>
    @endif
    <li class="nav-item">
        <button class="nav-link btn bg-transparent finder-activator px-0 px-lg-2" data-finder-activator>
            <i class="fa fa-search" aria-hidden="true"></i>
        </button>
    </li>
@endsection

@section('pageStyle')
    @parent
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/client/edit/chat.css') }}">
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/client/edit/chat_print.css') }}">
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/common/chat.css') }}">
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/common/chat_add_files.css') }}">
@endsection

@section('action_type_section')
    <div id="print_header" class="d-none"><h2>Чат {{ $client->address }}</h2><hr/></div>
    <div id="message_list" data-finder-content>
        <div class="text-center pt-3 text-secondary">
            <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
        </div>
    </div>
    <form id="delete_message" action="{{ route('manager.client.chat.delete', $client->id) }}" method="post" class="d-none">
        @csrf
        @method('PATCH')
        <input type="hidden" name="message_id" id="delete_message_id">
    </form>
    <form id="set_chat_draft">
        <input type="hidden" name="client_id" value="{{ $client->id }}">
        <input type="hidden" name="manager_id" value="{{ auth()->user()->id }}">
        <input type="hidden" id="chat_draft_message_text" name="text" value="{{ $chat_draft?->text }}"/>
        <input type="hidden" id="chat_draft_reply_message_id" name="reply_message_id" value="{{ $chat_draft?->reply_message_id }}">
        <input type="hidden" id="chat_draft_edit_message_id" name="edit_message_id" value="{{ $chat_draft?->edit_message_id }}">
    </form>
    <div id="add_message_block" class="mt-3 pb-3">
        <div id="add_message_loading_block" class="d-none">
            <i class="fa fa-spinner fa-pulse fa-4x text-secondary"></i>
        </div>
        <div id="scroll_to_bottom_div">
            <button id="scroll_to_bottom_btn" class="btn bg-secondary btn-sm">
                <i class="fa fa-chevron-down" aria-hidden="true"></i>
            </button>
        </div>
        <div id="reply_message_block">
            <div class="callout callout-info bg-light position-relative">
                <div id="reply_message_cancel">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </div>
                <p id="reply_message_block_author" class="text-info text-truncate m-0"></p>
                <p id="reply_message_block_text" class="text-truncate"></p>
            </div>
        </div>
        <div id="edit_message_block">
            <div class="callout callout-info bg-light position-relative">
                <div id="edit_message_cancel">
                    <i class="fa fa-times" aria-hidden="true"></i>
                </div>
                <p class="text-info text-truncate m-0"><i class="fa fa-pencil mr-2" aria-hidden="true"></i>Редактирование</p>
                <p id="edit_message_block_text" class="text-truncate"></p>
            </div>
        </div>
        <div id="files_preview_block"></div>
        <form id="add_message" action="{{ route('manager.client.chat.store', $client->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="client_id" value="{{ $client->id }}">
            <input type="hidden" id="reply_message_id" name="reply_message_id" value="{{ $chat_draft?->reply_message_id }}">
            <input type="hidden" id="edit_message_id" name="edit_message_id" value="{{ $chat_draft?->edit_message_id }}">
            <input type="file" id="add_message_files" name="add_message_files[]" multiple class="d-none">
            <div class="form-group">
                <label for="chat_text">Ваше сообщение</label>
                <textarea id="chat_text" class="form-control" name="text" rows="3" placeholder="Ваше сообщение" tabindex="-2">{{ $chat_draft?->text }}</textarea>
            </div>
            @if (empty($client->is_active))
                <div class="pull-left text-danger pt375">
                    <i class="fa fa-exclamation-triangle mr-1" aria-hidden="true"></i> Объект завершён
                </div>
            @else
                <div class="pull-left">
                    @if (!empty($client->chat_remember[auth()->user()->id]))
                        <a class="btn btn-info active" href="{{ route('manager.client.chat_remember', [$client->id,0]) }}"><i class="fa fa-check-square-o mr-1" aria-hidden="true"></i> Напоминать</a>
                    @else
                        <a class="btn btn-info" href="{{ route('manager.client.chat_remember', [$client->id,1]) }}"><i class="fa fa-square-o mr-1" aria-hidden="true"></i> Напоминать</a>
                    @endif
                </div>
            @endif
            <div class="pull-right">
                <button id="add_file" class="btn bg-lightblue">
                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                </button>
                <button type="submit" id="add_message_button" class="btn btn-primary" @if (empty($client->is_active)) disabled="disabled" @endif>
                    <i class="fa fa-paper-plane-o mr-2" aria-hidden="true"></i>Отправить
                </button>
            </div>
        </form>
    </div>

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
    @parent
    <!-- JQuery Star Rating -->
    <script src="{{ asset('plugins/jquery-star-rating/jquery.star-rating.js') }}"></script>
    <script src="{{ assetVersioned('dist/js/pages/manager/client/edit/chat.js') }}"></script>
    <script src="{{ assetVersioned('dist/js/pages/common/chat.js') }}"></script>
    <script src="{{ assetVersioned('dist/js/pages/common/chat_add_files.js') }}"></script>
@endsection
