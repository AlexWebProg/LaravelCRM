@extends('manager.layouts.main')

@section('pageName'){{ $manager->name }}@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">{{ $manager->name }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('manager.manager.index',$manager->is_admin) }}">{{ empty($manager->is_admin) ? 'Сотрудники' : 'Руководство' }}</a></li>
                        <li class="breadcrumb-item active">{{ $manager->name }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="card">
                    {{--<div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link {{ (empty(request()->route()->parameters['action_type'])) ? 'active' : '' }}" href="{{ route('manager.manager.edit', $manager->id) }}">Управление</a>
                            </li>
                        </ul>
                    </div><!-- /.card-header -->--}}
                    <div class="card-body">
                        @yield('action_type_section')
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
