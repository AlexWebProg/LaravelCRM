@extends('manager.layouts.main')

@section('pageName'){{ empty(request()->route()->parameters['is_admin']) ? 'Сотрудники' : 'Руководство' }}@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/manager/index.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">{{ empty(request()->route()->parameters['is_admin']) ? 'Сотрудники' : 'Руководство' }}</h1>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

                @if (auth()->user()->is_admin)
                <div class="row mb-3">
                    <div class="col-md-3">
                        <a href="{{ route('manager.manager.create',request()->route()->parameters['is_admin']) }}" class="btn btn-block btn-primary"><i class="fa fa-plus mr-2" aria-hidden="true"></i> Добавить {{ empty(request()->route()->parameters['is_admin']) ? 'сотрудника' : 'руководителя' }}</a>
                    </div>
                </div>
                @endif

                <div class="card pl-0 pr-0">
                    <div class="card-body pl-0 pr-0">
                        <table id="managersTable" class="table table-bordered table-striped text-center">
                            <thead>
                            <tr>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Телефон</th>
                                <th>Создан</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($managers as $manager)
                                <tr>
                                    <td><a class="manager_name_href" href="{{ route('manager.manager.edit', $manager->id) }}">{{ $manager->name }}</a></td>
                                    <td>{{ $manager->email }}</td>
                                    <td>{{ $manager->phone_str }}</td>
                                    <td data-order="{{ $manager->created_at }}">{{ $manager->created_str }}</td>
                                    <td><a class="btn btn-primary btn-sm" href="{{ route('manager.manager.edit', $manager->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Телефон</th>
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
    <script src="{{ assetVersioned('dist/js/pages/manager/manager/index.js') }}"></script>
@endsection
