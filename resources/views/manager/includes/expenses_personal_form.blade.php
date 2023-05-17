<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                @if (isset($expense?->category) && empty($expense->category))
                    <h3 class="card-title">Данные по приходу</h3>
                @else
                    <h3 class="card-title">Данные по @can('create_expense_income',$manager)приходу или @endcanрасходу</h3>
                @endcan
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fa fa-minus" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <form id="main_form" action="{{ (!empty($expense?->id)) ? route('manager.expenses_personal.update', $expense->id) : route('manager.expenses_personal.store') }}" method="post" class="pb-5">
                    @csrf
                    @if (!empty($expense?->id)) @method('patch') @else @method('put') @endif
                    <div class="form-group">
                        <label for="date">Дата</label>
                        <input type="text" class="form-control datetimepicker-input @error('date') is-invalid @enderror" id="date" data-toggle="datetimepicker" data-target="#date" name="date" value="{{ old('date', empty($expense?->date_str) ? date('d.m.Y',time()) : $expense->date_str) }}" placeholder="Дата ..." @if (!empty($expense?->transfer_id))disabled="disabled"@endif/>
                        @error('date')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    @if (empty($expense?->transfer_id))
                    <div class="form-group">
                        <label>@can('create_expense_income',$manager)Тип: приход или категория расхода@elseКатегория расхода@endcan</label>
                        <select id="expense_category" class="form-control @error('category') is-invalid @enderror" name="category">
                            @foreach ($expense_form_data['expense_available_categories'] as $category_id => $category_name)
                            <option value="{{ $category_id }}"
                                {{ $category_id == old('category', $expense?->category) ? ' selected' : '' }}>
                                {{ $category_name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    @endif
                    <div id="transfer_to_block" class="form-group d-none">
                        <label>Передача: кому</label>
                        <select class="form-control @error('transfer_to') is-invalid @enderror" name="transfer_to" @if (!empty($expense?->transfer_id))disabled="disabled"@endif>
                            <option value="0"
                                {{ null == old('transfer_to', $expense?->transfer_to) ? ' selected' : '' }}>
                                Выберите ...
                            </option>
                            @foreach ($expense_form_data['managers'] as $manager)
                                <option value="{{ $manager->id }}"
                                    {{ $manager->id == old('transfer_to', $expense?->transfer_to) ? ' selected' : '' }}>
                                    {{ $manager->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('transfer_to')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="sum">Сумма</label>
                        <div class="input-group">
                            <input type="text" class="form-control text-right @error('sum') is-invalid @enderror" id="sum" name="sum_string" value="{{ old('sum_string', $expense?->sum_string) }}" @if (!empty($expense?->transfer_id))disabled="disabled"@endif/>
                            <input type="hidden" name="sum" value="{{ old('sum', $expense?->sum_rub) }}" @if (!empty($expense?->transfer_id))disabled="disabled"@endif>
                            <div class="input-group-append">
                                <div class="input-group-text"></div>
                            </div>
                        </div>
                        @error('sum')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="comment">Пояснение</label>
                        <textarea class="form-control @error('comment') is-invalid @enderror" rows="3" name="comment" placeholder="Пояснение ..." @if (!empty($expense?->transfer_id))disabled="disabled"@endif>{{ old('comment', $expense?->comment) }}</textarea>
                        @error('comment')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <input type="hidden" name="manager_id" value="{{ $manager_id }}">
                    <input type="hidden" name="page" value="{{ $page }}">
                    <input id="main_form_submit" type="submit" class="d-none">
                </form>
            </div>
        </div>
    </div>
    @if (!empty($expense?->id) && empty($expense->transfer_id))
        @can('edit_current_expense_personal',$expense)
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
                        <form action="{{ route('manager.expenses_personal.delete', $expense->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="manager_id" value="{{ $manager_id }}">
                            <input type="hidden" name="page" value="{{ $page }}">
                            <button class="btn btn-danger pull-right confirm_expense_delete"><i class="fa fa-trash mr-2"></i> Удалить этот расход или приход</button>
                        </form>
                    </div>
                </div>
            </div>
         @endcan
    @endif
</div>


@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/manager/expenses_personal/form.js') }}"></script>
@endsection
