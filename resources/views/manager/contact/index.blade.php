@extends('manager.layouts.main')

@section('pageName')Контакты сотрудников@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/contact/index.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">Контакты сотрудников</h1>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content pb-3">
            <div class="container-fluid">

                @if (auth()->user()->is_admin)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <a href="{{ route('manager.contact.create') }}" class="btn btn-block btn-primary"><i class="fa fa-plus mr-2" aria-hidden="true"></i> Добавить контакт</a>
                    </div>
                </div>
                @endif

                <div class="card pl-0 pr-0">
                    <div class="card-body pl-0 pr-0">
                        <table id="contactsTable" class="table table-bordered table-striped text-center">
                            <thead>
                            <tr>
                                <th class="desktop">Порядок</th>
                                <th class="all">Имя</th>
                                <th class="desktop">Должность</th>
                                <th class="min-tablet-l">Телефон</th>
                                <th class="desktop">Описание</th>
                                <th class="desktop">Активен</th>
                                <th class="all"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($contacts as $contact)
                                <tr>
                                    <td>{{ $contact->sort }}</td>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->job }}</td>
                                    <td class="text-nowrap">{{ $contact->phone_str }}</td>
                                    <td>{{ $contact->about }}</td>
                                    <td class="text-center" data-order="{{ $contact->is_active }}">
                                        <i class="fa fa-{{ empty($contact->is_active) ? 'times' : 'check' }}" aria-hidden="true"></i>
                                    </td>
                                    <td><a class="btn btn-primary btn-sm" href="{{ route('manager.contact.edit', $contact->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Порядок</th>
                                <th>Имя</th>
                                <th>Должность</th>
                                <th>Телефон</th>
                                <th>Описание</th>
                                <th>Активен</th>
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
    <script src="{{ assetVersioned('dist/js/pages/manager/contact/index.js') }}"></script>
@endsection
