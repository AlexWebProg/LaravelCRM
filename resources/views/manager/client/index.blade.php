@extends('manager.layouts.main')

@section('pageName'){{ $pageName }}@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/client/index.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">{{ $pageName }}</h1>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content pb-3">
            <div class="container-fluid">

                @if (!empty(request()->route()->parameters['ob_status']) && request()->route()->parameters['ob_status'] === '1' && auth()->user()->is_admin)
                <div class="row mb-3">
                    <div class="col-md-4">
                        <a href="{{ route('manager.client.create') }}" class="btn btn-block btn-primary"><i class="fa fa-plus mr-2" aria-hidden="true"></i> Добавить объект</a>
                    </div>
                </div>
                @endif

                <div class="card pl-0 pr-0">
                    <div class="card-body pl-0 pr-0">
                        <table id="clientsTable" class="table table-bordered table-striped text-center">
                            <thead>
                            <tr>
                                <th class="all">Адрес</th>
                                <th class="min-tablet-l">Имя</th>
                                <th class="desktop">Email</th>
                                <th class="desktop">Телефон</th>
                                <th class="desktop">Координаты</th>
                                <th class="desktop">{{ !empty($ob_status_2_page) ? 'На гарантии до' : 'Создан' }}</th>
                                <th class="control"></th>
                                <th class="control"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $client)
                                <tr>
                                    <td class="client-table-address cursor-pointer" onclick="location.href='{{ route('manager.client.edit', $client->id) }}'">
                                        {{ $client->address }}
                                        <span class="badge bg-danger d-none client-table-badge" data-client_id="{{ $client->id }}">0</span>
                                        @if (!empty($client->chat_remember[auth()->user()->id]) || !empty($client->task_remember[auth()->user()->id]))
                                            <span class="badge navbar-badge rounded-circle bg-danger chat-remember-badge">&nbsp;</span>
                                        @endif
                                    </td>
                                    <td>{{ $client->name }}</td>
                                    <td class="text-nowrap">{{ $client->email }}</td>
                                    <td class="text-nowrap">
                                        <a href="tel:+{{ $client->phone }}">
                                            {{ $client->phone_str }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="https://yandex.ru/maps/?rtext=~{{$client->coordinates}}" target="_blank">
                                            {{ $client->coordinates }}
                                        </a>
                                    </td>
                                    <td class="text-nowrap" data-order="{{ !empty($ob_status_2_page) ? $client->warranty_end : $client->created_at }}">{{ !empty($ob_status_2_page) ? $client->warranty_end_str : $client->created_str }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm dtr-control-phone"><i class="fa fa-phone" aria-hidden="true"></i></button>
                                    </td>
                                    <td data-order="{{ $client->type_order }}"></td>
                                </tr>
                            @endforeach
                            @if (!empty($ob_status_2_objects))
                            <tr class="bg-gray">
                                <td colspan="7" class="py-3"><h5 class="m-0">Объекты на гарантии</h5></td>
                                <td class="d-none"></td>
                                <td class="d-none"></td>
                                <td class="d-none"></td>
                                <td class="d-none"></td>
                                <td class="d-none"></td>
                                <td class="d-none"></td>
                                <td data-order="19"></td>
                            </tr>
                            @endif
                            @if (!empty($demo_objects))
                            <tr class="bg-gray">
                                <td colspan="7" class="py-3"><h5 class="m-0">Объекты для тестирования</h5></td>
                                <td class="d-none"></td>
                                <td class="d-none"></td>
                                <td class="d-none"></td>
                                <td class="d-none"></td>
                                <td class="d-none"></td>
                                <td class="d-none"></td>
                                <td data-order="29"></td>
                            </tr>
                            @endif
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Адрес</th>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Телефон</th>
                                <th>Координаты</th>
                                <th>{{ !empty($ob_status_2_page) ? 'На гарантии до' : 'Создан' }}</th>
                                <th></th>
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
    <script src="{{ assetVersioned('dist/js/pages/manager/client/index.js') }}"></script>
@endsection
