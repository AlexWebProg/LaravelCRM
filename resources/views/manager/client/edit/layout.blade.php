@extends('manager.layouts.main')

@section('pageName'){{ $client->address }}@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/client/edit/layout.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">{{ $client->address }} {{ $client->ob_status === 3 ? '(завершён)' : ( $client->ob_status === 2 ? '(на гарантии)' : '') }}</h1>
                    <ol class="breadcrumb one_line_breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('manager.client.index',$client->ob_status) }}">
                                {{ $client->ob_status === 3 ? 'Завершённые объекты' : ( $client->ob_status === 2 ? 'Объекты на гарантии' : 'Объекты') }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active">{{ $client->address }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="card" id="client_edit_form" data-client_id="{{ $client->id }}">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link {{ (empty(request()->route()->parameters['action_type'])) ? 'active' : '' }}" href="{{ route('manager.client.edit', $client->id) }}">Объект</a>
                            </li>
                            @can('show_webcam')
                            <li class="nav-item">
                                <a class="nav-link {{ (!empty(request()->route()->parameters['action_type']) && request()->route()->parameters['action_type'] == 'webcam') ? 'active' : '' }}" href="{{ route('manager.client.edit', [$client->id,'webcam']) }}">Камера</a>
                            </li>
                            @endcan
                            @can('show_photo')
                            <li class="nav-item">
                                <a class="nav-link position-relative {{ (!empty(request()->route()->parameters['action_type']) && request()->route()->parameters['action_type'] == 'photo') ? 'active' : '' }}" href="{{ route('manager.client.edit', [$client->id,'photo']) }}">
                                    Фото
                                    <span class="badge navbar-badge rounded-circle badge-danger d-none newPhotoBadge">0</span>
                                </a>
                            </li>
                            @endcan
                            @can('show_plan')
                            <li class="nav-item">
                                <a class="nav-link position-relative {{ (!empty(request()->route()->parameters['action_type']) && request()->route()->parameters['action_type'] == 'plan') ? 'active' : '' }}" href="{{ route('manager.client.edit', [$client->id,'plan']) }}">
                                    План работ
                                    <span class="badge navbar-badge rounded-circle badge-danger d-none newActiveTasksBadge">0</span>
                                    @if (!empty($client->task_remember[auth()->user()->id]))
                                        <span class="badge navbar-badge rounded-circle bg-danger chat-remember-badge">&nbsp;</span>
                                    @endif
                                </a>
                            </li>
                            @endcan
                            @can('show_estimate')
                            <li class="nav-item">
                                <a class="nav-link position-relative {{ (!empty(request()->route()->parameters['action_type']) && request()->route()->parameters['action_type'] == 'estimate') ? 'active' : '' }}" href="{{ route('manager.client.edit', [$client->id,'estimate']) }}">
                                    Смета
                                    <span class="badge navbar-badge rounded-circle badge-danger d-none newEstimateBadge">0</span>
                                </a>
                            </li>
                            @endcan
                            @can('show_chat')
                            <li class="nav-item">
                                <a class="nav-link position-relative {{ (!empty(request()->route()->parameters['action_type']) && request()->route()->parameters['action_type'] == 'chat') ? 'active' : '' }}" href="{{ route('manager.client.edit', [$client->id,'chat']) }}">
                                    Чат
                                    <span class="badge navbar-badge rounded-circle badge-danger d-none newChatMessageBadge">0</span>
                                    @if (!empty($client->chat_remember[auth()->user()->id]))
                                        <span class="badge navbar-badge rounded-circle bg-danger chat-remember-badge">&nbsp;</span>
                                    @endif
                                </a>
                            </li>
                            @endcan
                            @can('show_tech_doc')
                            <li class="nav-item">
                                <a class="nav-link position-relative {{ (!empty(request()->route()->parameters['action_type']) && request()->route()->parameters['action_type'] == 'tech_doc') ? 'active' : '' }}" href="{{ route('manager.client.edit', [$client->id,'tech_doc']) }}">
                                    Тех. док.
                                    <span class="badge navbar-badge rounded-circle badge-danger d-none newTechDocBadge">0</span>
                                </a>
                            </li>
                            @endcan
                            @can('show_expenses_object')
                            <li class="nav-item">
                                <a class="nav-link position-relative {{ (!empty(request()->route()->parameters['action_type']) && request()->route()->parameters['action_type'] == 'expenses') ? 'active' : '' }}" href="{{ route('manager.client.edit', [$client->id,'expenses']) }}">
                                    Расходы
                                </a>
                            </li>
                            @endcan
                            <li class="nav-item">
                                <a class="nav-link {{ (!empty(request()->route()->parameters['action_type']) && request()->route()->parameters['action_type'] == 'stat') ? 'active' : '' }}" href="{{ route('manager.client.edit', [$client->id,'stat']) }}">Статистика</a>
                            </li>
                            @can('show_master_estimate')
                                <li class="nav-item">
                                    <a class="nav-link position-relative {{ (!empty(request()->route()->parameters['action_type']) && request()->route()->parameters['action_type'] == 'master_estimate') ? 'active' : '' }}" href="{{ route('manager.client.edit', [$client->id,'master_estimate']) }}">
                                        Смета для мастера
                                        <span class="badge navbar-badge rounded-circle badge-danger d-none newMasterEstimateBadge">0</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        @yield('action_type_section')
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/manager/client/edit/layout.js') }}"></script>
@endsection
