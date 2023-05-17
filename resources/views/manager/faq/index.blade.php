@extends('manager.layouts.main')

@section('pageName')Частые вопросы@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/faq/index.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">Частые вопросы</h1>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content pb-3">
            <div class="container-fluid">

                @if (auth()->user()->is_admin)
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <a href="{{ route('manager.faq.create') }}" class="btn btn-block btn-primary"><i class="fa fa-plus mr-2" aria-hidden="true"></i> Добавить вопрос</a>
                        </div>
                    </div>
                @endif

                <div class="card pl-0 pr-0">
                    <div class="card-body pl-0 pr-0">
                        <table id="faqsTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th class="desktop text-center">Порядок</th>
                                <th class="all text-center">Вопрос</th>
                                <th class="desktop text-center">Ответ</th>
                                <th class="desktop text-center">Активен</th>
                                <th class="all"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($faqs as $faq)
                                <tr>
                                    <td class="text-center">{{ $faq->sort }}</td>
                                    <td class="faq_text">{!! $faq->question !!}</td>
                                    <td class="faq_text">{!! $faq->answer_urlified !!}</td>
                                    <td class="text-center" data-order="{{ $faq->is_active }}">
                                        <i class="fa fa-{{ empty($faq->is_active) ? 'times' : 'check' }}" aria-hidden="true"></i>
                                    </td>
                                    <td class="text-center"><a class="btn btn-primary btn-sm" href="{{ route('manager.faq.edit', $faq->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="text-center">Порядок</th>
                                <th class="text-center">Вопрос</th>
                                <th class="text-center">Ответ</th>
                                <th class="text-center">Активен</th>
                                <th></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/manager/faq/index.js') }}"></script>
@endsection
