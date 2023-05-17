@extends('manager.layouts.main')

@section('pageName')Отчёт по расходам@endsection

@section('navbarItem')
    <li class="nav-item">
        <button class="nav-link btn bg-transparent finder-activator px-0 px-lg-2" data-finder-activator>
            <i class="fa fa-search" aria-hidden="true"></i>
        </button>
    </li>
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Мой отчёт по расходам</h1>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body" data-finder-content>
                        @include('manager.includes.expenses_personal_index',['page' => 'my_expenses'])
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection
