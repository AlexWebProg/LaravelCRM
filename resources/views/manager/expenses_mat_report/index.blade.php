@extends('manager.layouts.main')

@section('pageName')Отчёт по материалам@endsection

@section('navbarItem')
    <li class="nav-item">
        <button class="nav-link btn bg-transparent viewport_zoom_btn px-0 px-lg-2" data-content="width=1440, initial-scale=1">
            <i class="fa fa-search-minus" aria-hidden="true"></i>
        </button>
    </li>
@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/mat_report/index.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">Отчёт по материалам</h1>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content pb-3">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-inline">
                                    <form id="month_year_form" action="{{ route('manager.expenses_mat_report.change_month_year') }}" method="post">
                                        @csrf
                                        <label for="month_year" class="d-none d-sm-inline mr-3">Год и месяц:</label>
                                        <input type="text" class="form-control datetimepicker-input" id="month_year" data-toggle="datetimepicker" data-target="#month_year" name="month_year" value="{{ $month_report['month_year_str'] }}" placeholder="Год и месяц отчёта ..." />
                                    </form>
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('manager.expenses_mat_report.year_report', $month_report['year']) }}" class="btn btn-primary"><i class="fa fa-calendar mr-2" aria-hidden="true"></i>Общая <span class="d-none d-sm-inline">статистика </span>за год</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pl-0 pr-0">
                        @if (auth()->user()->is_admin)
                            <div id="table_buttons_block">
                                <div id="table_buttons_block_content">
                                    <a href="{{ route('manager.expenses_mat_report.export_excel', $month_report['year']) }}" class="btn btn-primary table_button"><i class="fa fa-file-excel-o mr-2" aria-hidden="true"></i>Сохранить в excel</a>
                                </div>
                            </div>
                        @endif
                        <table id="matReport" class="table table-bordered matReport">
                            <thead>
                            <tr>
                                <th class="date">Дата</th>
                                <th class="goods">Краткое описание товара</th>
                                <th class="goods">Чеки/сумма</th>
                                <th class="tools">Кому/какой?</th>
                                <th class="tools">Инструменты/сумма</th>
                                <th class="auto">Кто?</th>
                                <th class="auto">Заправки/сумма</th>
                                <th class="salary">Кому?</th>
                                <th class="salary">Ребятам/сумма</th>
                                <th class="transfer">Передача/пояснение/сумма</th>
                                <th class="received">Получено</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($month_report['data'] as $row)
                                <tr>
                                    <td class="text-nowrap date">{{ $row['date'] }}</td>
                                    <td class="text-left goods">{!! $row['goods_info'] !!}</td>
                                    <td class="text-right text-nowrap goods">{{ $row['goods_sum_str'] }}</td>
                                    <td class="text-left tools">{!! $row['tools_info'] !!}</td>
                                    <td class="text-right text-nowrap tools">{{ $row['tools_sum_str'] }}</td>
                                    <td class="text-left auto">{!! $row['auto_info'] !!}</td>
                                    <td class="text-right text-nowrap auto">{{ $row['auto_sum_str'] }}</td>
                                    <td class="text-left salary">{!! $row['salary_info'] !!}</td>
                                    <td class="text-right text-nowrap salary">{{ $row['salary_sum_str'] }}</td>
                                    <td class="text-left transfer">{!! $row['transfer_info'] !!}</td>
                                    <td class="text-left received">{!! $row['received_info'] !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="column_sum">Итого:</th>
                                <th colspan="2" class="text-right text-nowrap goods">{{ $month_report['column_sum']['goods_sum_str'] }}</th>
                                <th colspan="2" class="text-right tools text-nowrap">{{ $month_report['column_sum']['tools_sum_str'] }}</th>
                                <th colspan="2" class="text-right auto text-nowrap">{{ $month_report['column_sum']['auto_sum_str'] }}</th>
                                <th colspan="2" class="text-right salary text-nowrap">{{ $month_report['column_sum']['salary_sum_str'] }}</th>
                                <th class="transfer"></th>
                                <th class="text-right received text-nowrap">{{ $month_report['column_sum']['received_sum_str'] }}</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="text-center text-nowrap total_spent">Итого потрачено:</th>
                                <th colspan="6" class="text-center text-nowrap total_spent">{{ $month_report['total_spent_str'] }}</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="text-center text-nowrap received">Итого остаток:</th>
                                <th colspan="6" class="text-center text-nowrap received">{{ $month_report['total_left_str'] }}</th>
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
    <!-- JQuery FloatingScroll -->
    <script src="{{ asset('plugins/floatingscroll/jquery.floatingscroll.min.js') }}"></script>
    <script src="{{ assetVersioned('dist/js/pages/manager/mat_report/index.js') }}"></script>
@endsection
