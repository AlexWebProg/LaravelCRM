<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Title -->
    <title>КомпанияМечты Личный кабинет</title>
    <meta name="description" content="КомпанияМечты личный кабинет">
    <meta name="image" content="{{ asset('favicon.png') }}">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
    <meta property="og:title" content="КомпанияМечты Личный кабинет">
    <meta property="og:description" content="КомпанияМечты личный кабинет">
    <meta property="og:image" content="{{ asset('favicon.png') }}">
    <!-- PWA -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('pwa_lk/lk_apple_180x180.png') }}">
    <link rel="apple-touch-startup-image" sizes="192x192" href="{{ asset('pwa_lk/lk_192x192.png') }}" />
    <link rel="manifest" href="{{ asset('pwa_lk/manifest.webmanifest') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
          href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
    <!-- JQuery Confirm-->
    <link rel="stylesheet" href="{{ asset('plugins/jquery-confirm-v3.3.4/css/jquery-confirm.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/DataTables-1-12-1/datatables.min.css') }}">
    <!-- BS Stepper -->
    <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">
    <!-- Checkbox iOS -->
    <link rel="stylesheet" href="{{ asset('plugins/checkbox-ios/checkbox-ios.css') }}">
    <!-- JQuery FloatingScroll -->
    <link rel="stylesheet" href="{{ asset('plugins/floatingscroll/jquery.floatingscroll.css') }}">
    <!-- FancyBox -->
    <link rel="stylesheet" href="{{ asset('plugins/fancybox/fancybox.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fancybox/panzoom.css') }}">
    <!-- JQuery text finder -->
    <link rel="stylesheet" href="{{ assetVersioned('plugins/jquery-text-finder/style.css') }}">
    <!-- Our custom css -->
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/client.css') }}">
    <!-- Custom CSS for current page -->
    @yield('pageStyle')
</head>
<body class="hold-transition sidebar-mini layout-fixed" data-finder-wrapper data-finder-scroll-offset="175">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        <img class="animation__shake" src="{{ asset('dist/img/VLogo.png') }}" alt="BBLogo" height="60"
             width="60">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light px-0">
        <div class="col-12 d-flex justify-content-between px-0">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fa fa-bars" aria-hidden="true"></i></a>
                </li>
                <li class="nav-item">
                    <button class="nav-link btn bg-transparent moveToTop"><i class="fa fa-chevron-up mr-2" aria-hidden="true"></i>@yield('pageName')</button>
                </li>
            </ul>
            <ul class="navbar-nav">
                @yield('navbarItem')
                <li class="nav-item">
                    <form id="logOutForm" data-id="{{ auth()->user()->id }}" action="{{ route('logout') }}" method="post">
                        @csrf
                        <button class="nav-link btn bg-transparent" data-statclick="logout" type="submit"><i class="fa fa-sign-out" aria-hidden="true"></i></button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <!-- /.navbar -->

    @yield('submenu')
    @include('client.includes.sidebar')
    @yield('content')

    <!-- Notifications -->
    @if (!empty(session('notification')['message']))
        <div class="toasts-top-right fixed">
            <div class="toast bg-{{ !empty(session('notification')['class']) ? session('notification')['class'] : 'success'}} fade show mw-100 w-auto" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-center">
                    <div>
                        @if(!empty(session('notification')['icon']))
                            <i class="fa fa-{{ session('notification')['icon'] }} fa-lg" aria-hidden="true"></i>
                        @elseif (!empty(session('notification')['class']) && session('notification')['class'] == 'success')
                            <i class="fa fa-check fa-lg" aria-hidden="true"></i>
                        @elseif (!empty(session('notification')['class']) && session('notification')['class'] == 'danger')
                            <i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i>
                        @else
                            <i class="fa fa-envelope fa-lg" aria-hidden="true"></i>
                        @endif
                    </div>
                    <div class="ml-2 mr-2">
                        {!! session('notification')['message'] !!}
                    </div>
                    <div class="align-self-start">
                        <button data-dismiss="toast" type="button" class="close" aria-label="Close" onclick="$(this).closest('.toast').toast('hide');">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if ($errors->any())
        <div class="toasts-top-right fixed" id="validation_errors_notification">
            <div class="toast bg-danger fade show mw-100 w-auto" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-body d-flex align-items-center">
                    <div>
                        <i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true"></i>
                    </div>
                    <div class="ml-2 mr-2">
                        Данные не были сохранены.<br/>Проверьте правильность заполнения полей формы.<br/>Ошибки указаны рядом с неверно заполненными полями.
                    </div>
                    <div class="align-self-start">
                        <button data-dismiss="toast" type="button" class="close" aria-label="Close" onclick="$(this).closest('.toast').toast('hide');">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- /Notifications -->

    <footer class="main-footer text-truncate">
        <strong>{{ (auth()->user()->ob_status === 2) ? 'Объект на гарантийном обслуживании' : 'КомпанияМечты Личный кабинет' }}</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- Camera warning modal -->
<div class="modal fade" id="modal_webcam_warning">
    <div class="modal-dialog">
        <div class="modal-content">
            @if (auth()->user()->demo && empty(auth()->user()->webcam))
                <div class="modal-header">
                    <h4 class="modal-title">Камера</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>На объекте заказчика устанавливается веб-камера, доступная для онлайн-просмотра здесь.</p>
                    <p>В демо-режиме веб-камера недоступна.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times mr-2" aria-hidden="true"></i>Закрыть</button>
                </div>
            @else
                <div class="modal-header">
                    <h4 class="modal-title">Условия просмотра</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Вебкамера предназначена только для личного онлайн-просмотра.</p>
                    <p>Копирование, распространение или иное использование материала без письменного разрешения автора не допускается.</p>
                    <div class="custom-control custom-checkbox pt-2">
                        <input class="custom-control-input" type="checkbox" id="modal_webcam_warning_checkbox">
                        <label for="modal_webcam_warning_checkbox" class="custom-control-label">Я согласен</label>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle-o mr-2" aria-hidden="true"></i>Отмена</button>
                    <button type="button" id="modal_webcam_warning_link" onclick="window.open('{{ auth()->user()->webcam }}')"
                       data-statclick="webcam" data-dismiss="modal" class="btn btn-primary" disabled>
                        <i class="fa fa-check mr-2" aria-hidden="true"></i>Подтвердить
                    </button>
                </div>
            @endif
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Demo mode warning modal -->
<div class="modal fade" id="modal_demo_warning">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Демо-версия</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <i class="fa fa-ban mr-2" aria-hidden="true"></i>Эта функция доступна только для заказчиков.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times mr-2" aria-hidden="true"></i>Закрыть</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- bs-custom-file-input -->
<script src="{{ asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('plugins/moment/moment-with-locales.min.js') }}"></script>
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
<!-- JQuery Confirm-->
<script src="{{ asset('plugins/jquery-confirm-v3.3.4/js/jquery-confirm.js') }}"></script>
<!-- Checkbox iOS -->
<script src="{{ asset('plugins/checkbox-ios/checkbox-ios.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('plugins/DataTables-1-12-1/datatables.min.js') }}"></script>
<!-- jQuery UI Touch Punch -->
<script src="{{ asset('plugins/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js') }}"></script>
<!-- JQuery FloatingScroll -->
<script src="{{ asset('plugins/floatingscroll/jquery.floatingscroll.min.js') }}"></script>
<!-- InputMask -->
<script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- FancyBox -->
<script src="{{ asset('plugins/fancybox/fancybox_client.js') }}"></script>
<!-- GoogleDocViewer -->
<script src="{{ assetVersioned('dist/js/GoogleDocViewer.js') }}"></script>
<!-- JQuery text finder -->
<script src="{{ asset('plugins/jquery-text-finder/jquery.highlight.js') }}"></script>
<script src="{{ asset('plugins/jquery-text-finder/jquery.scrollto.js') }}"></script>
<script src="{{ assetVersioned('plugins/jquery-text-finder/jquery.finder.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.js') }}"></script>
<!-- Custom JS -->
<script src="{{ assetVersioned('dist/js/client.js') }}"></script>
<!-- Custom JS for current page -->
@yield('pageScript')

</body>
</html>
