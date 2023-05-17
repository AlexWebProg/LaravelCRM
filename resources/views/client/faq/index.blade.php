@extends('client.layouts.main')

@section('pageName')Частые вопросы@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/client/faq/index.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Частые вопросы</h1>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            @if (count($faqs))
                <div class="row mb-5">
                    <div class="col-12" id="faq">
                        @foreach ($faqs as $faq)
                        <div class="card">
                            <a class="d-block w-100 faq_question_link" data-toggle="collapse" href="#answer{{ $faq->id }}">
                                <div class="card-header">
                                    <h4 class="card-title w-100 position-relative">
                                        <div class="answer_toggle_icon"><i class="fa fa-chevron-down" aria-hidden="true"></i></div>
                                        <div class="faq_text faq_question_text">{!! $faq->question !!}</div>
                                    </h4>
                                </div>
                            </a>
                            <div id="answer{{ $faq->id }}" class="collapse" data-parent="#faq">
                                <div class="card-body">
                                    <div class="faq_text">{!! $faq->answer_urlified !!}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="container-fluid">
                    Информации ещё нет
                </div>
            @endif
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/client/faq/index.js') }}"></script>
@endsection
