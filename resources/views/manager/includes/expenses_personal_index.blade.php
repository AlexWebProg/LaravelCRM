@section('pageStyle')
    @parent
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/expenses_personal/index.css') }}">
@endsection

<div class="row mb-3">
    <div class="col-md-9">
        <h3 class="mb-3 mb-md-0">Остаток: {{ $strBalance }}</h3>
    </div>
    <div class="col-md-3">
        @if ($page === 'manager_expenses' && !empty($manager?->id))
            @can('edit_all_expenses_personal')
            <a href="{{ route('manager.expenses_all_personal.create',$manager->id) }}" class="btn btn-block btn-primary"><i class="fa fa-plus mr-2" aria-hidden="true"></i> Добавить @can('create_expense_income',$manager)приход или @endcanрасход</a>
            @endcan
        @elseif ($page === 'my_expenses')
            <a href="{{ route('manager.expenses_personal.create') }}" class="btn btn-block btn-primary"><i class="fa fa-plus mr-2" aria-hidden="true"></i> Добавить @can('create_expense_income')приход или @endcanрасход</a>
        @endif
    </div>
</div>
<hr/>

@if (count($arExpenses))
<div class="row">
    <div class="col-12">
        <div class="timeline mb-0">
            @foreach ($arExpenses as $idx => $expense)
                @if(empty($idx) || $expense['date'] !== $arExpenses[$idx - 1]['date'])
                    <div class="time-label">
                        <span class="bg-lightblue">{{ $expense['date'] }}</span>
                        <span class="ml-2 {{ ($expense['day_balance'] > 0) ? 'bg-success' : 'bg-gray' }}">{{ ($expense['day_balance'] > 0) ? '+' : '' }}{{ $expense['day_balance_str'] }}</span>
                    </div>
                @endif

                <div class="mr-0">
                    <i class="fa {{ !empty($expense['is_income']) ? 'fa-plus bg-success' : 'fa-minus bg-gray' }}"></i>
                    <div class="timeline-item">
                        @if ($expense['type'] === 'object')
                            @can('edit_current_expense_object',$expense['initial_object'])
                                <span class="time p-0"><a class="btn btn-primary btn-xs" href="{{ route('manager.expenses_object.edit', $expense['id']) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></span>
                            @endcan
                        @elseif ($expense['type'] === 'personal')
                            @if ($page === 'manager_expenses' && !empty($manager?->id))
                                @can('edit_current_expense_personal',$expense['initial_object'])
                                    <span class="time p-0"><a class="btn btn-primary btn-xs" href="{{ route('manager.expenses_all_personal.edit',[$manager->id,$expense['id']]) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></span>
                                @endcan
                            @elseif ($page === 'my_expenses')
                                <span class="time p-0"><a class="btn btn-primary btn-xs" href="{{ route('manager.expenses_personal.edit',$expense['id']) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></span>
                            @endif
                        @endif
                        <h3 class="timeline-header no-border text-bold {{ !empty($expense['is_income']) ? 'text-success' : '' }}">{{ !empty($expense['is_income']) ? '+' : '' }} {{ $expense['sum_str'] }}</h3>
                        <div class="timeline-body">{{ $expense['comment'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@else
    Пока приходов и расходов нет
@endif
