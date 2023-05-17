@extends('manager.layouts.main')

@section('pageName'){{ $faq->question }}@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0 text-nowrap overflow-hidden">{{ $faq->question }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('manager.faq.index') }}">Частые вопросы</a></li>
                        <li class="breadcrumb-item active">{{ $faq->question }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form id="main_form" action="{{ route('manager.faq.update', $faq->id) }}" method="post" class="pb-5">
                    @csrf
                    @method('patch')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Данные вопроса</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Вопрос</label>
                                        <textarea class="form-control @error('question') is-invalid @enderror" rows="3" name="question" placeholder="Вопрос" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>{{ old('question', $faq->question) }}</textarea>
                                        @error('question')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Ответ</label>
                                        <textarea class="form-control @error('answer') is-invalid @enderror" rows="3" name="answer" placeholder="Ответ" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>{{ old('answer', $faq->answer) }}</textarea>
                                        @error('answer')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Настройки вопроса</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Активность</label>
                                        <select class="form-control @error('is_active') is-invalid @enderror" name="is_active" @if (empty(auth()->user()->is_admin))disabled="disabled"@endif>
                                            <option value="1"
                                                {{ 1 == old('is_active', $faq->is_active) ? ' selected' : '' }}>
                                                Активен
                                            </option>
                                            <option value="0"
                                                {{ 0 == old('is_active', $faq->is_active) ? ' selected' : '' }}>
                                                Не отображается
                                            </option>
                                        </select>
                                        @error('is_active')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Порядок сортировки</label>
                                        <input type="number" min="0" max="99999" class="form-control @error('sort') is-invalid @enderror" name="sort" placeholder="Порядок сортировки"
                                               value="{{ old('sort', $faq->sort) }}" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                                        @error('sort')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @if (auth()->user()->is_admin)
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
                                        <button class="btn btn-danger pull-right confirm_faq_delete"><i class="fa fa-trash mr-2"></i> Удалить вопрос</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="id" value="{{ $faq->id }}">
                    <input id="main_form_submit" type="submit" class="d-none">
                </form>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <form id="delete_form" class="d-none" action="{{ route('manager.faq.delete', $faq->id) }}" method="post">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/manager/faq/edit.js') }}"></script>
@endsection
