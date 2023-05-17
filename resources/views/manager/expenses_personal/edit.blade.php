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
                        <li class="breadcrumb-item"><a href="{{ route('manager.expenses_personal.index') }}">Мой отчёт по расходам</a></li>
                        <li class="breadcrumb-item active">{{ $expense->name }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @include('manager.includes.expenses_personal_form',[
    'page' => 'my_expenses',
    'expense' => $expense,
    'manager' => null,
    'manager_id' => auth()->user()->id,
    'expense_form_data' => $expense_form_data])
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
