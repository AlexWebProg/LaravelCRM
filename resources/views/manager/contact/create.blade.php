@extends('manager.layouts.main')

@section('pageName')Добавление контакта@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Добавление контакта</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('manager.contact.index') }}">Контакты сотрудников</a></li>
                        <li class="breadcrumb-item active">Добавление контакта</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form id="main_form" action="{{ route('manager.contact.store') }}" method="post" class="pb-5">
                    @csrf
                    @method('put')
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
                                               value="{{ old('name') }}" required="required">
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Должность</label>
                                        <input type="text" class="form-control @error('job') is-invalid @enderror" name="job" placeholder="Должность сотрудника"
                                               value="{{ old('job') }}" required="required">
                                        @error('job')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Телефон</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="Телефон"
                                               value="{{ old('phone') }}" required="required">
                                        @error('phone')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Описание</label>
                                        <textarea class="form-control @error('about') is-invalid @enderror" rows="3" name="about" placeholder="Описание: чем сотрудник занимается и по каким вопросам к нему можно обратиться">{{ old('about') }}</textarea>
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
                                        <select class="form-control @error('is_active') is-invalid @enderror" name="is_active">
                                            <option value="1"
                                                {{ 1 == old('is_active',1) ? ' selected' : '' }}>
                                                Активен
                                            </option>
                                            <option value="0"
                                                {{ 0 == old('is_active',1) ? ' selected' : '' }}>
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
                                               value="{{ old('sort',$sort_init) }}">
                                        @error('sort')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input id="main_form_submit" type="submit" class="d-none">
                </form>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
