@extends('manager.layouts.main')

@section('pageName')Настройки системы@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/settings/edit.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">Настройки системы</h1>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <form id="main_form" action="{{ route('manager.settings.update') }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title card_header_title">Электронная почта для получения оповещений</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>О новых сообщениях заказчиков в чате</label>
                                        <input type="text" class="form-control @error('chat_new_client_mes_email') is-invalid @enderror" name="chat_new_client_mes_email" placeholder="Email для получения оповещений о новых сообщениях заказчиков в чате"
                                               value="{{ old('chat_new_client_mes_email', $arSettings['chat_new_client_mes_email']) }}" required="required" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                                        @error('chat_new_client_mes_email')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>О технических документах заказчиков</label>
                                        <input type="text" class="form-control @error('tech_doc_email') is-invalid @enderror" name="tech_doc_email" placeholder="Email для получения оповещений о технических документах заказчиков"
                                               value="{{ old('tech_doc_email', $arSettings['tech_doc_email']) }}" required="required" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                                        @error('tech_doc_email')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title card_header_title">Демо-версия</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p>{{ url('/demo') }}</p>
                                    <p>
                                        <button id="copy_demo_link_btn" data-url="{{ url('/demo') }}" class="btn btn-primary"><i class="fa fa-copy mr-2"></i>Скопировать</button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input id="main_form_submit" type="submit" class="d-none">
                </form>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/manager/settings/edit.js') }}"></script>
@endsection
