@extends('manager.layouts.main')

@section('pageName')Результаты опросов@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/quiz/template/results_index.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">Результаты опросов</h1>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                @can('create_survey')
                <div class="row mb-3">
                    <div class="col-md-3">
                        <a href="{{ route('manager.quiz.survey.create') }}" class="btn btn-block btn-primary"><i class="fa fa-play mr-2" aria-hidden="true"></i> Провести опрос</a>
                    </div>
                </div>
                @endcan

                <div class="card pl-0 pr-0">
                    <div class="card-body pl-0 pr-0">
                        <table id="templatesTable" class="table table-bordered table-striped text-center">
                            <thead>
                            <tr>
                                <th>Шаблон</th>
                                <th>Ответили</th>
                                <th>Вводная фраза</th>
                                <th>Вопросов</th>
                                <th>Автор</th>
                                <th>Проведён</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($templates as $template)
                                <tr>
                                    <td><a class="template_name_href" href="{{ route('manager.quiz.template.show', $template->id) }}">{{ $template->name }}</a></td>
                                    <td>{{ $template->clients_completed->count() }} из {{ $template->clients->count() }}</td>
                                    <td class="intro">{{ $template->intro }}</td>
                                    <td>{{ $template->questions->count() }}</td>
                                    <td>{{ $template->author }}</td>
                                    <td data-order="{{ $template->last_survey_date() }}">{{ $template->last_survey_date_str() }}</td>
                                    <td><a class="btn btn-primary btn-sm" href="{{ route('manager.quiz.template.show', $template->id) }}"><i class="fa fa-external-link" aria-hidden="true"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Шаблон</th>
                                <th>Ответили</th>
                                <th>Вводная фраза</th>
                                <th>Вопросов</th>
                                <th>Автор</th>
                                <th>Проведён</th>
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
    <script src="{{ assetVersioned('dist/js/pages/manager/quiz/template/results_index.js') }}"></script>
@endsection
