@extends('manager.layouts.main')

@section('pageName'){{ $contact->name }}@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">{{ $contact->name }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('manager.contact.index') }}">Контакты сотрудников</a></li>
                        <li class="breadcrumb-item active">{{ $contact->name }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form id="main_form" action="{{ route('manager.contact.update', $contact->id) }}" method="post" class="pb-5">
                    @csrf
                    @method('patch')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Данные контакта</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Имя</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Имя сотрудника"
                                               value="{{ old('name', $contact->name) }}" required="required" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Должность</label>
                                        <input type="text" class="form-control @error('job') is-invalid @enderror" name="job" placeholder="Должность сотрудника"
                                               value="{{ old('job', $contact->job) }}" required="required" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                                        @error('job')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Телефон</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="Телефон"
                                               value="{{ old('phone', $contact->phone_str) }}" required="required" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                                        @error('phone')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Описание</label>
                                        <textarea class="form-control @error('about') is-invalid @enderror" rows="3" name="about" placeholder="Описание: чем сотрудник занимается и по каким вопросам к нему можно обратиться" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>{{ old('about', $contact->about) }}</textarea>
                                        @error('about')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Настройки контакта</h3>
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
                                                {{ 1 == old('is_active', $contact->is_active) ? ' selected' : '' }}>
                                                Активен
                                            </option>
                                            <option value="0"
                                                {{ 0 == old('is_active', $contact->is_active) ? ' selected' : '' }}>
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
                                               value="{{ old('sort', $contact->sort) }}" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
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
                                        <button class="btn btn-danger pull-right confirm_contact_delete"><i class="fa fa-trash mr-2"></i> Удалить контакт</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="id" value="{{ $contact->id }}">
                    <input id="main_form_submit" type="submit" class="d-none">
                </form>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <form id="delete_form" class="d-none" action="{{ route('manager.contact.delete', $contact->id) }}" method="post">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/manager/contact/edit.js') }}"></script>
@endsection
