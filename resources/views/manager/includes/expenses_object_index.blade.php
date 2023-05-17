@section('navbarItem')
    <li class="nav-item">
        <button class="nav-link btn bg-transparent viewport_zoom_btn px-0 px-lg-2" data-content="width=1440, initial-scale=1">
            <i class="fa fa-search-minus" aria-hidden="true"></i>
        </button>
    </li>
@endsection

@section('pageStyle')
    @parent
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/expenses_object/index.css') }}">
@endsection

@can('edit_expenses_object')
    <div id="table_buttons_block">
        <div id="table_buttons_block_content">
            <a href="{{ route('manager.expenses_object.create', $client_id) }}" class="btn btn-primary table_button"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Добавить<span class="d-none d-sm-inline"> строку</span></a>
            @if (auth()->user()->is_admin)
            <a href="{{ route('manager.expenses_object.export_excel') }}" class="btn btn-success table_button"><i class="fa fa-file-excel-o mr-2" aria-hidden="true"></i>Сохранить<span class="d-none d-sm-inline"> в excel</span></a>
            @endif
        </div>
    </div>
@endcan
<table id="expenses_object" class="table table-bordered expenses_object">
    <thead>
    <tr>
        <th>Дата</th>
        <th>Чеки и доставка/подробно</th>
        <th>
            <div class="money_header">Суммы по чекам</div>
            <hr class="my-2"/>
            <div class="text-right text-nowrap">{{ $expensesTotal['chk_amount'] }}</div>
        </th>
        <th>
            <div class="money_header">Суммы по мусору</div>
            <hr class="my-2"/>
            <div class="text-right text-nowrap">{{ $expensesTotal['garb_amount'] }}</div>
        </th>
        <th>Пояснение по инструменту</th>
        <th>
            <div class="money_header">Суммы по инструменту</div>
            <hr class="my-2"/>
            <div class="text-right text-nowrap">{{ $expensesTotal['tool_amount'] }}</div>
        </th>
        <th>
            <div class="money_header">Заложено по смете Сумма</div>
            <hr class="my-2"/>
            <div class="text-right text-nowrap">{{ $expensesTotal['estimate_sum_1'] }}</div>
        </th>
        <th class="{{ !empty($expenses_warnings['materials_danger']) ? 'expense_danger' : (!empty($expenses_warnings['materials_warning']) ? 'expense_warning' : '') }}">
            <div class="money_header">Остаток Сумма</div>
            <hr class="my-2"/>
            <div class="text-right text-nowrap">{{ $expensesTotal['remainder_sum_1'] }}</div>
        </th>
        <th>Оплата работ, кому и за что подробно</th>
        <th>
            <div class="money_header">Суммы полученные</div>
            <hr class="my-2"/>
            <div class="text-right text-nowrap">{{ $expensesTotal['received_sum'] }}</div>
        </th>
        <th>
            <div class="money_header">Заложено по смете Сумма</div>
            <hr class="my-2"/>
            <div class="text-right text-nowrap">{{ $expensesTotal['estimate_sum_2'] }}</div>
        </th>
        <th class="{{ !empty($expenses_warnings['work_danger']) ? 'expense_danger' : (!empty($expenses_warnings['work_warning']) ? 'expense_warning' : '') }}">
            <div class="money_header">Остаток Сумма</div>
            <hr class="my-2"/>
            <div class="text-right text-nowrap">{{ $expensesTotal['remainder_sum_2'] }}</div>
        </th>
        <th>Автор</th>
        @canany(['edit_expenses_object_estimate','edit_expenses_object'])
            <th>
                @if (!empty($client_id))
                    @can('edit_expenses_object_estimate')
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#estimateModal"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                    @endcan
                @endif
            </th>
        @endcanany
    </tr>
    </thead>
    <tbody>
    @foreach($expenses as $expense)
        <tr>
            <td>{{ $expense->date_str }}</td>
            <td>{{ $expense->сhk_and_del_det }}</td>
            <td class="text-right text-nowrap money">{{ $expense->chk_amount_str }}</td>
            <td class="text-right text-nowrap money">{{ $expense->garb_amount_str }}</td>
            <td>{{ $expense->tool_comment }}</td>
            <td class="text-right text-nowrap money">{{ $expense->tool_amount_str }}</td>
            <td class="money"></td>
            <td class="money"></td>
            <td>{{ $expense->work_pay }}</td>
            <td class="text-right text-nowrap money">{{ $expense->received_sum_str }}</td>
            <td class="money"></td>
            <td class="money"></td>
            <td>{{ $expense->author }}</td>
            @canany(['edit_expenses_object_estimate','edit_expenses_object'])
                <td>
                    @can('edit_current_expense_object',$expense)
                        <a class="btn btn-primary btn-sm" href="{{ route('manager.expenses_object.edit', $expense->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                    @endcan
                </td>
            @endcanany
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Дата</th>
        <th>Чеки и доставка/подробно</th>
        <th>Суммы по чекам</th>
        <th>Суммы по мусору</th>
        <th>Пояснение по инструменту</th>
        <th>Суммы по инструменту</th>
        <th>Заложено по смете Сумма</th>
        <th>Остаток Сумма</th>
        <th>Оплата работ, кому и за что подробно</th>
        <th>Суммы полученные</th>
        <th>Заложено по смете Сумма</th>
        <th>Остаток Сумма</th>
        <th>Автор</th>
        @canany(['edit_expenses_object_estimate','edit_expenses_object'])
            <th></th>
        @endcanany
    </tr>
    </tfoot>
</table>

@can('edit_expenses_object_estimate')
    @if (!empty($client_id))
        <div class="modal fade" id="estimateModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Заложено по смете</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('manager.expenses_object.update_estimate', $client_id) }}" method="post" class="pb-5">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <label for="expense_estimate_sum_1">Материалы</label>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right @error('expense_estimate_sum_1') is-invalid @enderror" id="expense_estimate_sum_1" name="expense_estimate_sum_1_string" value="{{ old('expense_estimate_sum_1_string', $expensesTotal['estimate_sum_1_string']) }}"/>
                                    <input type="hidden" name="expense_estimate_sum_1" value="{{ old('expense_estimate_sum_1', $expensesTotal['estimate_sum_1_form']) }}">
                                    <div class="input-group-append">
                                        <div class="input-group-text"></div>
                                    </div>
                                </div>
                                @error('expense_estimate_sum_1')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="expense_estimate_sum_2">Работы</label>
                                <div class="input-group">
                                    <input type="text" class="form-control text-right @error('expense_estimate_sum_2') is-invalid @enderror" id="expense_estimate_sum_2" name="expense_estimate_sum_2_string" value="{{ old('expense_estimate_sum_2_string', $expensesTotal['estimate_sum_2_string']) }}"/>
                                    <input type="hidden" name="expense_estimate_sum_2" value="{{ old('expense_estimate_sum_2', $expensesTotal['estimate_sum_2_form']) }}">
                                    <div class="input-group-append">
                                        <div class="input-group-text"></div>
                                    </div>
                                </div>
                                {{--<div class="input-group">
                                    <input type="text" class="form-control text-right @error('expense_estimate_sum_2') is-invalid @enderror" id="expense_estimate_sum_2" name="expense_estimate_sum_2" value="{{ old('expense_estimate_sum_2', $expensesTotal['estimate_sum_2_form']) }}"/>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-rub" aria-hidden="true"></i></div>
                                    </div>
                                </div>--}}
                                @error('expense_estimate_sum_2')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <input id="modal_estimate_form_submit" type="submit" class="d-none">
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle-o" aria-hidden="true"></i><i class="fa fa-close-o mr-2" aria-hidden="true"></i>Отмена</button>
                        <button type="button" class="btn btn-primary" id="modal_estimate_form_submit_button"><i class="fa fa-check mr-2" aria-hidden="true"></i>Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endcan

@section('pageScript')
    @parent
    <script src="{{ assetVersioned('dist/js/pages/manager/expenses_object/index.js') }}"></script>
    <script>
        new $.fn.dataTable.FixedColumns( expenses_object, {
            left: 1,
            right: @can('edit_expenses_object') 1 @else 0 @endcan
        } );
    </script>
@endsection
