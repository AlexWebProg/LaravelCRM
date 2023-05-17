@extends('manager.layouts.main')

@section('pageName')Отчёт по материалам за {{ $year }} г@endsection

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
                <h1 class="mt-0">Отчёт по материалам за {{ $year }} г</h1>
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
                                    <form id="year_form" action="{{ route('manager.expenses_mat_report.change_year') }}" method="post">
                                        @csrf
                                        <label for="input_year" class="d-none d-sm-inline mr-3">Год:</label>
                                        <input type="text" class="form-control datetimepicker-input" id="input_year" data-toggle="datetimepicker" data-target="#input_year" name="input_year" value="{{ $year }}" placeholder="Год отчёта ..." />
                                    </form>
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('manager.expenses_mat_report.index') }}" class="btn btn-primary"><i class="fa fa-calendar mr-2" aria-hidden="true"></i>Стат<span class="d-none d-sm-inline">истика</span> за месяц</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pl-0 pr-0">
                        @if (auth()->user()->is_admin)
                            <div id="table_buttons_block">
                                <div id="table_buttons_block_content">
                                    <a href="{{ route('manager.expenses_mat_report.export_excel', $year) }}" class="btn btn-primary table_button"><i class="fa fa-file-excel-o mr-2" aria-hidden="true"></i>Сохранить в excel</a>
                                </div>
                            </div>
                        @endif
                        <table id="matReport" class="table table-bordered matReport">
                            <thead>
                            <tr>
                                <th class="date">Месяц</th>
                                <th class="goods">Чеки/сумма</th>
                                <th class="tools">Инструменты/сумма</th>
                                <th class="auto">Заправки/сумма</th>
                                <th class="salary">Ребятам/сумма</th>
                                <th class="received">Получено</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($year_report['months'] as $row)
                                <tr>
                                    <td class="text-nowrap date">{{ $row['month_str'] }}</td>
                                    <td class="text-right text-nowrap goods">{{ $row['column_sum']['goods_sum_str'] }}</td>
                                    <td class="text-right text-nowrap tools">{{ $row['column_sum']['tools_sum_str'] }}</td>
                                    <td class="text-right text-nowrap auto">{{ $row['column_sum']['auto_sum_str'] }}</td>
                                    <td class="text-right text-nowrap salary">{{ $row['column_sum']['salary_sum_str'] }}</td>
                                    <td class="text-right received">{{ $row['column_sum']['received_sum_str'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="column_sum">Итого:</th>
                                <th class="text-right text-nowrap goods">{{ $year_report['goods_sum_str'] }}</th>
                                <th class="text-right tools text-nowrap">{{ $year_report['tools_sum_str'] }}</th>
                                <th class="text-right auto text-nowrap">{{ $year_report['auto_sum_str'] }}</th>
                                <th class="text-right salary text-nowrap">{{ $year_report['salary_sum_str'] }}</th>
                                <th class="text-right received text-nowrap">{{ $year_report['received_sum_str'] }}</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-center text-nowrap total_spent">Итого потрачено:</th>
                                <th colspan="3" class="text-center text-nowrap total_spent">{{ $year_report['total_spent_str'] }}</th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-center text-nowrap received">Итого остаток:</th>
                                <th colspan="3" class="text-center text-nowrap received">{{ $year_report['total_left_str'] }}</th>
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
