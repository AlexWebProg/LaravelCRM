@extends('manager.layouts.main')

@section('pageName')Добавление @can('create_expense_income')прихода или @endcanрасхода@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Добавление @can('create_expense_income')прихода или @endcanрасхода</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('manager.expenses_personal.index') }}">Мой отчёт по расходам</a></li>
                        <li class="breadcrumb-item active">Добавление @can('create_expense_income')прихода или @endcanрасхода</li>
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
    'expense' => null,
    'manager' => null,
    'manager_id' => auth()->user()->id,
    'expense_form_data' => $expense_form_data])
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
