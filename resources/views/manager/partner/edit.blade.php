@extends('manager.layouts.main')

@section('pageName'){{ $partner->name }}@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">{{ $partner->name }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('manager.partner.index') }}">Партнёры</a></li>
                        <li class="breadcrumb-item active">{{ $partner->name }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form id="main_form" action="{{ route('manager.partner.update', $partner->id) }}" method="post" class="pb-5">
                    @csrf
                    @method('patch')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Данные партнёра</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Имя / Название</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Имя или название партнёра"
                                               value="{{ old('name', $partner->name) }}" required="required" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Описание</label>
                                        <textarea class="form-control @error('about') is-invalid @enderror" rows="3" name="about" placeholder="Описание: чем партнёр занимается" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>{{ old('about', $partner->about) }}</textarea>
                                        @error('about')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Телефон</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="Телефон партнёра"
                                               value="{{ old('phone', $partner->phone_str) }}" required="required" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                                        @error('phone')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Сайт</label>
                                        <input type="text" class="form-control @error('site') is-invalid @enderror" name="site" placeholder="Адрес сайта партнёра"
                                               value="{{ old('site', $partner->site) }}" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
                                        @error('job')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Настройки партнёра</h3>
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
                                                {{ 1 == old('is_active', $partner->is_active) ? ' selected' : '' }}>
                                                Активен
                                            </option>
                                            <option value="0"
                                                {{ 0 == old('is_active', $partner->is_active) ? ' selected' : '' }}>
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
                                               value="{{ old('sort', $partner->sort) }}" @if (empty(auth()->user()->is_admin))readonly="readonly"@endif>
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
                                        <button class="btn btn-danger pull-right confirm_partner_delete"><i class="fa fa-trash mr-2"></i> Удалить партнёра</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="id" value="{{ $partner->id }}">
                    <input id="main_form_submit" type="submit" class="d-none">
                </form>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <form id="delete_form" class="d-none" action="{{ route('manager.partner.delete', $partner->id) }}" method="post">
        @csrf
        @method('DELETE')
    </form>
@endsection

@section('pageScript')
    <script src="{{ assetVersioned('dist/js/pages/manager/partner/edit.js') }}"></script>
@endsection
