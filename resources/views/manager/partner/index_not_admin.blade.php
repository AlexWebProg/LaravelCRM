@extends('manager.layouts.main')

@section('pageName')Партнёры@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0">Партнёры</h1>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            @if (count($partners))
                <div class="card card-solid mb-5">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($partners as $partner)
                                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                    <div class="card bg-light d-flex flex-fill">
                                        <div class="card-header text-muted border-bottom-0"></div>
                                        <div class="card-body pt-0">
                                            <h2 class="lead"><b>{{ $partner->name }}</b></h2>
                                            @if (!empty($partner->about))
                                                <p class="text-muted text-sm">{{ $partner->about }}</p>
                                            @endif
                                            @if (!empty($partner->phone) || !empty($partner->site))
                                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                                @if (!empty($partner->site))
                                                    <li class="small"><span class="fa-li"><i class="fa fa-external-link"></i></span> Сайт: <a href="{{ $partner->site }}" target="_blank">{{ $partner->site }}</a></li>
                                                @endif
                                                @if (!empty($partner->phone))
                                                    <li class="small"><span class="fa-li"><i class="fa fa-phone"></i></span> Тел: <a href="tel:+{{ $partner->phone }}">{{ $partner->phone_str }}</a></li>
                                                @endif
                                            </ul>
                                            @endif
                                        </div>
                                        @if (!empty($partner->phone) || !empty($partner->site))
                                        <div class="card-footer">
                                            <div class="text-right">
                                                @if (!empty($partner->site))
                                                <a href="{{ $partner->site }}" target="_blank" class="btn btn-sm bg-teal">
                                                    <i class="fa fa-external-link mr-2"></i>Сайт
                                                </a>
                                                @endif
                                                @if (!empty($partner->phone))
                                                <a href="tel:+{{ $partner->phone }}" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-phone mr-2"></i>Позвонить
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="container-fluid">
                    Партнёров ещё нет
                </div>
            @endif
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
