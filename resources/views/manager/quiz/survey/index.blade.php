@extends('manager.layouts.main')

@section('pageName')Результаты опросов@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/quiz/survey/index.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">Проведённые опросы</h1>
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
                        <table id="surveysTable" class="table table-bordered table-striped text-center">
                            <thead>
                            <tr>
                                <th>Шаблон</th>
                                <th>Ответили</th>
                                <th>Участники</th>
                                <th>Автор</th>
                                <th>Создан</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($surveys as $survey)
                                <tr>
                                    <td><a class="survey_name_href" href="{{ route('manager.quiz.survey.show', $survey->id) }}">{{ $survey->template->name }}</a></td>
                                    <td>{{ $survey->clients_completed->count() }} из {{ $survey->clients->count() }}</td>
                                    <td>{{ $survey->clients->count() > 1 ? 'несколько' : $survey->clients->first()->client->address }}</td>
                                    <td>{{ $survey->author }}</td>
                                    <td data-order="{{ $survey->created_at }}">{{ $survey->created_str }}</td>
                                    <td><a class="btn btn-primary btn-sm" href="{{ route('manager.quiz.survey.show', $survey->id) }}"><i class="fa fa-external-link" aria-hidden="true"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Шаблон</th>
                                <th>Ответили</th>
                                <th>Участники</th>
                                <th>Автор</th>
                                <th>Создан</th>
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
    <script src="{{ assetVersioned('dist/js/pages/manager/quiz/survey/index.js') }}"></script>
@endsection
