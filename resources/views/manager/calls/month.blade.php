@extends('manager.layouts.main')

@section('pageName')Аналитика звонков@endsection

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
                <h1 class="mt-0">Аналитика звонков</h1>
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
                                    <form id="month_year_form" action="{{ route('manager.calls.change_month') }}" method="post">
                                        @csrf
                                        <label for="month_year" class="d-none d-sm-inline mr-3">Год и месяц:</label>
                                        <input type="text" class="form-control datetimepicker-input" id="month_year" data-toggle="datetimepicker" data-target="#month_year" name="month_year" value="{{ $month['month_year_str'] }}" placeholder="Год и месяц ..." />
                                    </form>
                                </div>
                            </div>
                            <div class="col-6 text-right">
                                <a href="{{ route('manager.calls.year', $month['year']) }}" class="btn btn-primary"><i class="fa fa-calendar mr-2" aria-hidden="true"></i>Всего за {{ $month['year'] }} г</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pl-0 pr-0">
                        @if (auth()->user()->is_admin)
                            <div id="table_buttons_block">
                                <div id="table_buttons_block_content">
                                    <a href="{{ route('manager.calls.export_excel', $month['year']) }}" class="btn btn-primary table_button"><i class="fa fa-file-excel-o mr-2" aria-hidden="true"></i>Сохранить в excel</a>
                                </div>
                            </div>
                        @endif
                        <table id="calls" class="table table-bordered calls">
                            <thead>
                            <tr>
                                <th class="date">Дата</th>
                                <th class="repair_full">Ремонт целиком</th>
                                <th class="repair_partial">Частичный ремонт</th>
                                <th class="advertising">Реклама</th>
                                <th class="evening_calls">Звонки после 19:00</th>
                                <th class="day_total">Всего за день</th>
                                <th class="signed_up">Записались</th>
                                <th class="est_wo_dep">Сметы без выезда</th>
                                <th class="from">Откуда были звонки</th>
                                <th class="author">Автор</th>
                                @can('edit_calls')
                                    <th class="date"></th>
                                @endcan
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($month['data'] as $row)
                                <tr>
                                    <td class="text-center text-nowrap date">{{ $row['date'] }}</td>
                                    <td class="text-right text-nowrap repair_full">{{ $row['repair_full'] }}</td>
                                    <td class="text-right text-nowrap repair_partial">{{ $row['repair_partial'] }}</td>
                                    <td class="text-right text-nowrap advertising">{{ $row['advertising'] }}</td>
                                    <td class="text-right text-nowrap evening_calls">{{ $row['evening_calls'] }}</td>
                                    <td class="text-right text-nowrap day_total">{{ $row['day_total'] }}</td>
                                    <td class="text-right text-nowrap signed_up">{{ $row['signed_up'] }}</td>
                                    <td class="text-right text-nowrap est_wo_dep">{{ $row['est_wo_dep'] }}</td>
                                    <td class="text-right from">{!! $row['from'] !!}</td>
                                    <td class="text-center author">{{ $row['author'] }}</td>
                                    @can('edit_calls')
                                        <td class="date">
                                            <a class="btn btn-primary btn-sm" href="{{ route('manager.calls.edit', $row['db_date']) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        </td>
                                    @endcan
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="column_sum">Итого:</th>
                                <th class="text-right text-nowrap repair_full">{{ $month['column_sum']['repair_full'] }}</th>
                                <th class="text-right text-nowrap repair_partial">{{ $month['column_sum']['repair_partial'] }}</th>
                                <th class="text-right text-nowrap advertising">{{ $month['column_sum']['advertising'] }}</th>
                                <th class="text-right text-nowrap evening_calls">{{ $month['column_sum']['evening_calls'] }}</th>
                                <th class="text-right text-nowrap day_total">{{ $month['column_sum']['day_total'] }}</th>
                                <th class="text-right text-nowrap signed_up">{{ $month['column_sum']['signed_up'] }}</th>
                                <th class="text-right text-nowrap est_wo_dep">{{ $month['column_sum']['est_wo_dep'] }}</th>
                                <th class="text-right from">{!! $month['column_sum']['from'] !!}</th>
                                <th class="author"></th>
                                @can('edit_calls')
                                    <th class="date"></th>
                                @endcan
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
    <script>
        new $.fn.dataTable.FixedColumns( calls, {
            left: 1,
            right: @can('edit_calls') 1 @else 0 @endcan
        } );
    </script>
@endsection
