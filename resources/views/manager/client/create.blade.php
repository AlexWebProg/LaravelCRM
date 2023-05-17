@extends('manager.layouts.main')

@section('pageName')Добавление объекта@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Добавление объекта</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('manager.client.index',1) }}">Объекты</a></li>
                        <li class="breadcrumb-item active">Добавление объекта</li>
                    </ol>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <form id="main_form" action="{{ route('manager.client.store') }}" method="post" class="pb-5">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Данные объекта</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Адрес объекта</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" rows="3" name="address" placeholder="Адрес объекта" required="required">{{ old('address') }}</textarea>
                                        @error('address')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Имя заказчика</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Имя заказчика"
                                               value="{{ old('name') }}" required="required">
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Телефон</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" placeholder="Телефон"
                                               value="{{ old('phone') }}">
                                        @error('phone')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email"
                                               value="{{ old('email') }}" required="required">
                                        @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Координаты</label>
                                        <input type="text" class="form-control @error('coordinates') is-invalid @enderror" name="coordinates" placeholder="Координаты адреса на карте"
                                               value="{{ old('coordinates') }}">
                                        @error('coordinates')
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
