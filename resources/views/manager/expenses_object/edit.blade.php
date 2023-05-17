@extends('manager.layouts.main')

@section('pageName')Редактирование расхода{{ empty($expense->client_id) ? ' по гарантии' : '' }}@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Редактирование расхода{{ empty($expense->client_id) ? ' по гарантии' : ': '.$expense->client->address }}</h1>
                    <ol class="breadcrumb">
                        @if (empty($expense->client_id))
                            <li class="breadcrumb-item">
                                <a href="{{ route('manager.expenses_guarantee.index') }}">Расходы по гарантии</a>
                            </li>
                        @else
                            <li class="breadcrumb-item">
                            <a href="{{ route('manager.client.index',$expense->client->is_active) }}">
                                {{ empty($expense->client->is_active) ? 'Завершённые объекты' : 'Объекты' }}
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{ route('manager.client.edit', [$expense->client->id,'expenses']) }}">{{ $expense->client->address }}</a>
                        </li>
                        @endif
                        <li class="breadcrumb-item active">Редактирование расхода</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content pb-5">
            <div class="container-fluid">
                <form id="main_form" action="{{ route('manager.expenses_object.update', $expense->id) }}" method="post">
                    @csrf
                    @method('patch')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Дата</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="client_id" value="{{ $expense->client_id }}">
                                    <input id="main_form_submit" type="submit" class="d-none">
                                    <div class="form-group">
                                        <label for="date">Дата</label>
                                        <input type="text" class="form-control datetimepicker-input @error('date') is-invalid @enderror" id="date" data-toggle="datetimepicker" data-target="#date" name="date" value="{{ old('date', $expense->date_str) }}" placeholder="Дата ..."/>
                                        @error('date')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Чеки и доставка</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Чеки и доставка/подробно</label>
                                        <textarea class="form-control @error('сhk_and_del_det') is-invalid @enderror" rows="3" name="сhk_and_del_det" placeholder="Чеки и доставка/подробно ...">{{ old('сhk_and_del_det', $expense->сhk_and_del_det) }}</textarea>
                                        @error('сhk_and_del_det')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="chk_amount">Сумма по чекам</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-right @error('chk_amount') is-invalid @enderror" id="chk_amount" name="chk_amount_string" value="{{ old('chk_amount_string', $expense->chk_amount_string) }}"/>
                                            <input type="hidden" name="chk_amount" value="{{ old('chk_amount', $expense?->chk_amount_rub) }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text"></div>
                                            </div>
                                        </div>
                                        @error('chk_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Мусор</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="garb_amount">Сумма по мусору</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-right @error('garb_amount') is-invalid @enderror" id="garb_amount" name="garb_amount_string" value="{{ old('garb_amount_string', $expense->garb_amount_string) }}"/>
                                            <input type="hidden" name="garb_amount" value="{{ old('garb_amount', $expense?->garb_amount_rub) }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text"></div>
                                            </div>
                                        </div>
                                        @error('garb_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-indigo">
                                <div class="card-header">
                                    <h3 class="card-title">Инструменты</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Пояснение по инструменту</label>
                                        <textarea class="form-control @error('tool_comment') is-invalid @enderror" rows="3" name="tool_comment" placeholder="Пояснение по инструменту: кому/какой ...">{{ old('tool_comment', $expense->tool_comment) }}</textarea>
                                        @error('tool_comment')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="garb_amount">Сумма по инструменту</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-right @error('tool_amount') is-invalid @enderror" id="tool_amount" name="tool_amount_string" value="{{ old('tool_amount_string', $expense->tool_amount_string) }}"/>
                                            <input type="hidden" name="tool_amount" value="{{ old('tool_amount', $expense?->tool_amount_rub) }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text"></div>
                                            </div>
                                        </div>
                                        @error('tool_amount')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card card-olive">
                                <div class="card-header">
                                    <h3 class="card-title">Работы</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Оплата работ, кому и за что подробно</label>
                                        <textarea class="form-control @error('work_pay') is-invalid @enderror" rows="3" name="work_pay" placeholder="Оплата работ, кому и за что подробно ...">{{ old('work_pay', $expense->work_pay) }}</textarea>
                                        @error('work_pay')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="received_sum">Сумма по работам</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-right @error('received_sum') is-invalid @enderror" id="received_sum" name="received_sum_string" value="{{ old('received_sum_string', $expense->received_sum_string) }}"/>
                                            <input type="hidden" name="received_sum" value="{{ old('received_sum', $expense?->received_sum_rub) }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text"></div>
                                            </div>
                                        </div>
                                        @error('received_sum')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <hr/>
                <div class="row pt-3">
                    <div class="col-md-6">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Функции</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-footer">
                                <form action="{{ route('manager.expenses_object.delete', $expense->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger pull-right confirm_expense_delete"><i class="fa fa-trash mr-2"></i> Удалить расход</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/manager/expenses_object/form.js') }}"></script>
@endsection
