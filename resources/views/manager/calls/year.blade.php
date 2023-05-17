@extends('manager.layouts.main')

@section('pageName')Аналитика звонков за {{ $year }} г@endsection

@section('navbarItem')
    <li class="nav-item">
        <button class="nav-link btn bg-transparent viewport_zoom_btn px-0 px-lg-2" data-content="width=1440, initial-scale=1">
            <i class="fa fa-search-minus" aria-hidden="true"></i>
        </button>
    </li>
@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/calls/index.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">Аналитика звонков за {{ $year }} г</h1>
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
                                    <form id="year_form" action="{{ route('manager.calls.change_year') }}" method="post">
                                        @csrf
                                        <label for="input_year" class="d-none d-sm-inline mr-3">Год:</label>
                                        <input type="text" class="form-control datetimepicker-input" id="input_year" data-toggle="datetimepicker" data-target="#input_year" name="input_year" value="{{ $year }}" placeholder="Год ..." />
                                    </form>
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('manager.calls.month') }}" class="btn btn-primary"><i class="fa fa-calendar mr-2" aria-hidden="true"></i>Данные за месяц</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pl-0 pr-0">
                        @if (auth()->user()->is_admin)
                            <div id="table_buttons_block">
                                <div id="table_buttons_block_content">
                                    <a href="{{ route('manager.calls.export_excel', $year) }}" class="btn btn-primary table_button"><i class="fa fa-file-excel-o mr-2" aria-hidden="true"></i>Сохранить в excel</a>
                                </div>
                            </div>
                        @endif
                        <table id="calls" class="table table-bordered calls">
                            <thead>
                            <tr>
                                <th class="date">Месяц</th>
                                <th class="repair_full">Ремонт целиком</th>
                                <th class="repair_partial">Частичный ремонт</th>
                                <th class="advertising">Реклама</th>
                                <th class="evening_calls">Звонки после 19:00</th>
                                <th class="day_total">Всего за месяц</th>
                                <th class="signed_up">Записались</th>
                                <th class="est_wo_dep">Сметы без выезда</th>
                                <th class="from">Откуда были звонки</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($year_report['months'] as $row)
                                <tr>
                                    <td class="text-nowrap date">{{ $row['month_str'] }}</td>
                                    <td class="text-right repair_full">{{ $row['column_sum']['repair_full'] }}</td>
                                    <td class="text-right repair_partial">{{ $row['column_sum']['repair_partial'] }}</td>
                                    <td class="text-right advertising">{{ $row['column_sum']['advertising'] }}</td>
                                    <td class="text-right evening_calls">{{ $row['column_sum']['evening_calls'] }}</td>
                                    <td class="text-right day_total">{{ $row['column_sum']['day_total'] }}</td>
                                    <td class="text-right signed_up">{{ $row['column_sum']['signed_up'] }}</td>
                                    <td class="text-right est_wo_dep">{{ $row['column_sum']['est_wo_dep'] }}</td>
                                    <td class="text-right from">{!! $row['column_sum']['from'] !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="column_sum">Итого:</th>
                                <th class="text-right repair_full">{{ $year_report['column_sum']['repair_full'] }}</th>
                                <th class="text-right repair_partial">{{ $year_report['column_sum']['repair_partial'] }}</th>
                                <th class="text-right advertising">{{ $year_report['column_sum']['advertising'] }}</th>
                                <th class="text-right evening_calls">{{ $year_report['column_sum']['evening_calls'] }}</th>
                                <th class="text-right day_total">{{ $year_report['column_sum']['day_total'] }}</th>
                                <th class="text-right signed_up">{{ $year_report['column_sum']['signed_up'] }}</th>
                                <th class="text-right est_wo_dep">{{ $year_report['column_sum']['est_wo_dep'] }}</th>
                                <th class="text-right from">{!! $year_report['column_sum']['from'] !!}</th>
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
    <script src="{{ assetVersioned('dist/js/pages/manager/calls/index.js') }}"></script>
@endsection
