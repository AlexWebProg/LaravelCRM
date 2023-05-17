@extends('client.layouts.main')

@section('pageName')Смета@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/client/estimate/index.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div>
                    <h1 class="mt-0">Смета</h1>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if (!empty(auth()->user()->estimate_comment) && !empty(auth()->user()->estimate_comment->text))
                    <div class="callout callout-info">
                        <h5>Комментарии</h5>
                        <div id="estimate_comment">{!! auth()->user()->estimate_comment->text !!}</div>
                    </div>
                @endif
                @if (count(auth()->user()->estimate))
                    @foreach (auth()->user()->estimate as $estimate)
                        <div class="card {{ empty($loop->index) ? 'card-primary' : 'card-secondary' }}">
                            <div class="card-header long-text">
                                <h3 class="card-title">{{ empty($loop->index) ? 'Актуальная смета' : 'Смета' }} от {{ $estimate->created_str }}</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                        <i class="fa fa-expand"></i>
                                    </button>
                                </div>
                                <!-- /.card-tools -->
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body file_embed_card_body">
                                <iframe class="google_doc_viewer file_embed_iframe" src="" data-src="{{ url('storage/'.$estimate->src) }}"></iframe>
                            </div>
                            <!-- /.card-body -->
                            <div class="google_doc_viewer_loading overlay dark">
                                <i class="fa fa-spinner fa-pulse fa-3x"></i>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="pb-5">Смета пока отсутствует</div>
                @endif
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.Main content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
