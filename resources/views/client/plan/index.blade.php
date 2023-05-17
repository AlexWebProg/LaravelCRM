@extends('client.layouts.main')

@section('pageName')План работ@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">План работ</h1>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid pb-5">
                <div class="row">
                    <div class="col-12">
                        <div class="callout callout-info">
                            <h5>Сроки выполнения работ (по договору)</h5>
                            <p class="text_pre_line">{{ empty(auth()->user()->plan_dates) ? 'Нет информации' : auth()->user()->plan_dates }}</p>
                        </div>

                        <div class="callout callout-warning">
                            <h5>План работ</h5>
                            <p class="text_pre_line">{{ empty(auth()->user()->plan_questions) ? 'Нет информации' : auth()->user()->plan_questions }}</p>
                        </div>

                        <div class="callout callout-success">
                            <h5>Проверка выполненных работ (этапы)</h5>
                            <p class="text_pre_line">{{ empty(auth()->user()->plan_check) ? 'Нет информации' : auth()->user()->plan_check }}</p>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
