@extends('manager.layouts.main')

@section('pageName')Объекты - сводка@endsection

@section('navbarItem')
    <li class="nav-item">
        <button class="nav-link btn bg-transparent viewport_zoom_btn px-0 px-lg-2" data-content="width=1440, initial-scale=1">
            <i class="fa fa-search-minus" aria-hidden="true"></i>
        </button>
    </li>
@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/client_summary/index.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">Объекты - сводка</h1>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content pb-3">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-body pl-0 pr-0">
                        @if (auth()->user()->is_admin)
                            <div id="table_buttons_block">
                                <div id="table_buttons_block_content">
                                    <a href="{{ route('manager.client_summary.export_excel') }}" class="btn btn-primary table_button"><i class="fa fa-file-excel-o mr-2" aria-hidden="true"></i>Сохранить в excel</a>
                                </div>
                            </div>
                        @endif
                        <table id="clientSummary" class="table table-bordered clientSummary">
                            <thead>
                            <tr>
                                <th>Объект</th>
                                <th>Адрес</th>
                                <th>Сроки выполнения работ (по договору)</th>
                                <th>Мастер</th>
                                <th>Камера</th>
                                <th>План работ</th>
                                <th>Проверка выполненных работ (этапы)</th>
                                <th>Оплата</th>
                                <th>Доставки материала</th>
                                <th>Создан</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($clients as $client)
                                @if (!empty($client->ob_status_2_header))
                                <tr class="sub_header">
                                    <td colspan="10" class="text-left py-3"><h3 class="m-0">Объекты на гарантии</h3></td>
                                    <td class="d-none"></td>
                                    <td class="d-none"></td>
                                    <td class="d-none"></td>
                                    <td class="d-none"></td>
                                    <td class="d-none"></td>
                                    <td class="d-none"></td>
                                    <td class="d-none"></td>
                                    <td class="d-none"></td>
                                    <td class="d-none"></td>
                                </tr>
                                @endif
                                @if (!empty($client->demo_header))
                                    <tr class="sub_header">
                                        <td colspan="10" class="text-left py-3"><h3 class="m-0">Объекты для тестирования и демо</h3></td>
                                        <td class="d-none"></td>
                                        <td class="d-none"></td>
                                        <td class="d-none"></td>
                                        <td class="d-none"></td>
                                        <td class="d-none"></td>
                                        <td class="d-none"></td>
                                        <td class="d-none"></td>
                                        <td class="d-none"></td>
                                        <td class="d-none"></td>
                                    </tr>
                                @endif
                                <tr class="@if (in_array($client->id,config('global.arTestAndDemoObjects'))) test_objects @elseif ($client->ob_status === 2) ob_status_2 @elseif ($client->in_process && $client->one_month_before_deadline) one_month_before_deadline @elseif ($client->in_process && $client->two_month_before_deadline) two_month_before_deadline @elseif ($client->in_process) in_process @endif" data-client="{{ $client->id }}">
                                    <td class="client-table-address" data-link="{{ route('manager.client.edit', $client->id) }}">
                                        @if (!empty($client->expense_warnings['materials_warning']) || !empty($client->expense_warnings['materials_danger']))
                                        <p class="badge {{ !empty($client->expense_warnings['materials_danger']) ? 'bg-danger' : 'bg-warning' }} expense_warning {{ (!empty($client->expense_warnings['work_warning']) || !empty($client->expense_warnings['work_danger'])) ? 'mb-1' : '' }}">Расх. на матер.</p>
                                        @endif
                                        @if (!empty($client->expense_warnings['work_warning']) || !empty($client->expense_warnings['work_danger']))
                                        <p class="badge {{ !empty($client->expense_warnings['work_danger']) ? 'bg-danger' : 'bg-warning' }} expense_warning">Расх. на работы</p>
                                        @endif
                                        {{ $client->address }}
                                        <span class="badge bg-danger d-none client-table-badge" data-client_id="{{ $client->id }}">0</span>
                                        @if (!empty($client->chat_remember[auth()->user()->id]) || !empty($client->task_remember[auth()->user()->id]))
                                            <span class="badge navbar-badge rounded-circle bg-danger chat-remember-badge">&nbsp;</span>
                                        @endif
                                    </td>
                                    <td class="editable" data-client="{{ $client->id }}" data-name="summary_address" data-initial="{{ $client->summary_address }}" data-updated="{{ $client->summary_address }}">{{ $client->summary_address }}</td>
                                    <td class="editable" data-client="{{ $client->id }}" data-name="plan_dates" data-order="{{ $client->plan_dates_order }}" data-initial="{{ $client->plan_dates }}" data-updated="{{ $client->plan_dates }}">{{ $client->plan_dates }}</td>
                                    <td class="editable" data-client="{{ $client->id }}" data-name="summary_master" data-initial="{{ $client->summary_master }}" data-updated="{{ $client->summary_master }}">{{ $client->summary_master }}</td>
                                    <td class="editable" data-client="{{ $client->id }}" data-name="summary_webcam" data-initial="{{ $client->summary_webcam }}" data-updated="{{ $client->summary_webcam }}">{{ $client->summary_webcam }}</td>
                                    <td class="editable" data-client="{{ $client->id }}" data-name="plan_questions" data-initial="{{ $client->plan_questions }}" data-updated="{{ $client->plan_questions }}">{{ $client->plan_questions }}</td>
                                    <td class="editable" data-client="{{ $client->id }}" data-name="plan_check" data-initial="{{ $client->plan_check }}" data-updated="{{ $client->plan_check }}">{{ $client->plan_check }}</td>
                                    <td class="editable" data-client="{{ $client->id }}" data-name="summary_pay" data-initial="{{ $client->summary_pay }}" data-updated="{{ $client->summary_pay }}">{{ $client->summary_pay }}</td>
                                    <td class="editable" data-client="{{ $client->id }}" data-name="summary_delivery" data-initial="{{ $client->summary_delivery }}" data-updated="{{ $client->summary_delivery }}">{{ $client->summary_delivery }}</td>
                                    <td class="text-nowrap" data-order="{{ $client->created_at }}">{{ $client->created_str }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Объект</th>
                                <th>Адрес</th>
                                <th>Сроки выполнения работ (по договору)</th>
                                <th>Мастер</th>
                                <th>Камера</th>
                                <th>План работ</th>
                                <th>Проверка выполненных работ (этапы)</th>
                                <th>Оплата</th>
                                <th>Доставки материала</th>
                                <th>Создан</th>
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
    <div id="updateInfo" class="d-none" data-updated_at="{{ date('Y-m-d H:i:s') }}"></div>
@endsection

@section('pageScript')
    <!-- JQuery FloatingScroll -->
    <script src="{{ asset('plugins/floatingscroll/jquery.floatingscroll.min.js') }}"></script>
    <script src="{{ assetVersioned('dist/js/pages/manager/client_summary/index.js') }}"></script>
    <script src="{{ assetVersioned('dist/js/pages/manager/client/index.js') }}"></script>
@endsection
