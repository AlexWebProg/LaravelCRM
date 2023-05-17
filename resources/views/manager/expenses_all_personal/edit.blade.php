@extends('manager.layouts.main')

@section('pageName'){{ $expense->name }}@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">{{ $expense->name }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('manager.expenses_all_personal.managers_index') }}">Расходы сотрудников</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('manager.expenses_all_personal.expenses_index', $manager->id) }}">{{ $manager->name }}</a></li>
                        <li class="breadcrumb-item active">{{ $expense->name }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        @include('manager.includes.expenses_personal_form',[
    'page' => 'manager_expenses',
    'expense' => $expense,
    'manager' => $manager,
    'manager_id' => $manager->id,
    'expense_form_data' => $expense_form_data])
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->
@endsection
