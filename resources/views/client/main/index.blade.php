@extends('client.layouts.main')

@section('pageName')Рабочий стол@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/client/main/index.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <h1 class="mt-0 mb-2">Рабочий стол</h1>
                @if (auth()->user()->ob_status === 2)
                <div class="info-box mb-2 p-0 bg-lightblue">
                    <span class="info-box-icon"><i class="fa fa-cog"></i></span>
                    <div class="info-box-content p-0">
                        <h3 class="info-box-text d-none d-sm-block m-0 p-0">Объект на гарантийном обслуживании</h3>
                        <span class="d-sm-none">Объект на гарантийном обслуживании</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                @endif
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid pb-5">
                <div class="row">

                    @if (!empty(auth()->user()->webcam) || auth()->user()->demo)
                        <div class="col-lg-4 col-6">
                            <a href="javascript:void(0);" class="small-box webcam_link bg-white border border-dark">
                                <div class="inner d-flex p-0">
                                    <h3 class="w-100 align-self-center small-box-header text-center m-0 p-0">Камера</h3>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fa fa-video-camera" aria-hidden="true"></i>
                                </div>
                            </a>
                        </div>
                    @endif

                        <div class="col-lg-4 col-6">
                            <a href="{{ route('client.photos') }}" class="small-box bg-white border border-dark">
                                <div class="inner d-flex p-0">
                                    <h3 class="w-100 align-self-center small-box-header text-center m-0 p-0">Фото</h3>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fa fa-camera" aria-hidden="true"></i>
                                </div>
                                <span class="badge badge-danger small-box-badge d-none newPhotoBadge">0</span>
                            </a>
                        </div>

                        <div class="col-lg-4 col-6">
                            <a href="{{ route('client.plan') }}" class="small-box bg-white border border-dark">
                                <div class="inner d-flex p-0">
                                    <h3 class="w-100 align-self-center small-box-header text-center m-0 p-0">План работ</h3>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fa fa-info-circle" aria-hidden="true"></i>
                                </div>
                                <span class="badge badge-danger small-box-badge d-none newPlanBadge">0</span>
                            </a>
                        </div>

                        <div class="col-lg-4 col-6">
                            <a href="{{ route('client.estimate') }}" class="small-box bg-white border border-dark">
                                <div class="inner d-flex p-0">
                                    <h3 class="w-100 align-self-center small-box-header text-center m-0 p-0">Смета</h3>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fa fa-file-text-o" aria-hidden="true"></i>
                                </div>
                                <span class="badge badge-danger small-box-badge d-none newEstimateBadge">0</span>
                            </a>
                        </div>

                        <div class="col-lg-4 col-6">
                            <a href="{{ route('client.chat') }}" class="small-box bg-white border border-dark">
                                <div class="inner d-flex p-0">
                                    <h3 class="w-100 align-self-center small-box-header text-center m-0 p-0">Чат</h3>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fa fa-comments-o" aria-hidden="true"></i>
                                </div>
                                <span class="badge badge-danger small-box-badge d-none newChatMessageBadge">0</span>
                            </a>
                        </div>

                        <div class="col-lg-4 col-6">
                            <a href="{{ route('client.tech_doc') }}" class="small-box bg-white border border-dark">
                                <div class="inner d-flex p-0">
                                    <h3 class="w-100 align-self-center small-box-header text-center m-0 p-0">Техническая<br/>документация</h3>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fa fa-file-pdf-o" aria-hidden="true"></i>
                                </div>
                                <span class="badge badge-danger small-box-badge d-none newTechDocBadge">0</span>
                            </a>
                        </div>

                        <div class="col-lg-4 col-6">
                            <a href="https://www.dreamcompany.ru/priem-platezhej" data-statclick="pay" target="_blank" class="small-box bg-white border border-dark">
                                <div class="inner d-flex p-0">
                                    <h3 class="w-100 align-self-center small-box-header text-center m-0 p-0">Оплата</h3>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fa fa-credit-card" aria-hidden="true"></i>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 col-6">
                            <a href="{{ route('client.contact') }}" class="small-box bg-white border border-dark">
                                <div class="inner d-flex p-0">
                                    <h3 class="w-100 align-self-center small-box-header text-center m-0 p-0">Контакты<br/>сотрудников</h3>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fa fa-phone" aria-hidden="true"></i>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 col-6">
                            <a href="{{ route('client.partner') }}" class="small-box bg-white border border-dark">
                                <div class="inner d-flex p-0">
                                    <h3 class="w-100 align-self-center small-box-header text-center m-0 p-0">Партнёры</h3>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fa fa-bookmark-o" aria-hidden="true"></i>
                                </div>
                            </a>
                        </div>

                        <div class="col-lg-4 col-6">
                            <a href="{{ route('client.faq') }}" class="small-box bg-white border border-dark">
                                <div class="inner d-flex p-0">
                                    <h3 class="w-100 align-self-center small-box-header text-center m-0 p-0">Частые<br/>вопросы</h3>
                                </div>
                                <div class="icon">
                                    <i class="nav-icon fa fa-info-circle" aria-hidden="true"></i>
                                </div>
                            </a>
                        </div>

                </div>

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
