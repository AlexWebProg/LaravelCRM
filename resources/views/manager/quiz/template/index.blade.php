@extends('manager.layouts.main')

@section('pageName')Шаблоны опросов@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/quiz/template/index.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">Шаблоны опросов</h1>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                <div class="row mb-3">
                    <div class="col-md-3">
                        <a href="{{ route('manager.quiz.template.create') }}" class="btn btn-block btn-primary"><i class="fa fa-plus mr-2" aria-hidden="true"></i> Добавить шаблон</a>
                    </div>
                </div>

                <div class="card pl-0 pr-0">
                    <div class="card-body pl-0 pr-0">
                        <table id="templatesTable" class="table table-bordered table-striped text-center">
                            <thead>
                            <tr>
                                <th>Название</th>
                                <th>Вводная фраза</th>
                                <th>Вопросов</th>
                                <th>Автор</th>
                                <th>Создан</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($templates as $template)
                                <tr>
                                    <td><a class="template_name_href" href="{{ route('manager.quiz.template.edit', $template->id) }}">{{ $template->name }}</a></td>
                                    <td class="intro">{{ $template->intro }}</td>
                                    <td>{{ $template->questions->count() }}</td>
                                    <td>{{ $template->author }}</td>
                                    <td data-order="{{ $template->created_at }}">{{ $template->created_str }}</td>
                                    <td><a class="btn btn-primary btn-sm" href="{{ route('manager.quiz.template.edit', $template->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Название</th>
                                <th>Вводная фраза</th>
                                <th>Вопросов</th>
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
    <script src="{{ assetVersioned('dist/js/pages/manager/quiz/template/index.js') }}"></script>
@endsection
