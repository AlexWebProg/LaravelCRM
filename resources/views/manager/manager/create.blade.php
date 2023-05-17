@extends('manager.layouts.main')

@section('pageName')Добавление {{ empty(request()->route()->parameters['is_admin']) ? 'сотрудника' : 'руководителя' }}@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Добавление {{ empty(request()->route()->parameters['is_admin']) ? 'сотрудника' : 'руководителя' }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('manager.manager.index',request()->route()->parameters['is_admin']) }}">{{ empty(request()->route()->parameters['is_admin']) ? 'Сотрудники' : 'Руководство' }}</a></li>
                        <li class="breadcrumb-item active">Добавление {{ empty(request()->route()->parameters['is_admin']) ? 'сотрудника' : 'руководителя' }}</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form id="main_form" action="{{ route('manager.manager.store') }}" method="post" class="pb-5">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Данные {{ empty(request()->route()->parameters['is_admin']) ? 'сотрудника' : 'руководителя' }}</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Имя</label>
                                        <input type="text" class="form-control" name="name" placeholder="Имя {{ empty(request()->route()->parameters['is_admin']) ? 'сотрудника' : 'руководителя' }}"
                                               value="{{ old('name') }}" required="required">
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Телефон</label>
                                        <input type="text" class="form-control" name="phone" placeholder="Телефон"
                                               value="{{ old('phone') }}">
                                        @error('phone')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control" name="email" placeholder="Email"
                                               value="{{ old('email') }}" required="required">
                                        @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="is_admin" value="{{ request()->route()->parameters['is_admin'] }}">
                    <input id="main_form_submit" type="submit" class="d-none">
                </form>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
