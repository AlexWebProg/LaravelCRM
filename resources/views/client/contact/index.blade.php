@extends('client.layouts.main')

@section('pageName')Контакты сотрудников@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="mb-2">
                    <h1 class="mt-0">Контакты сотрудников</h1>
                </div>
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            @if (auth()->user()->demo)
                <div class="container-fluid">
                    Этот раздел доступен только заказчикам.
                </div>
            @elseif (count($contacts))
                <div class="card card-solid mb-5">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($contacts as $contact)
                                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                                    <div class="card bg-light d-flex flex-fill">
                                        <div class="card-header text-muted border-bottom-0">
                                            {{ $contact->job }}
                                        </div>
                                        <div class="card-body pt-0">
                                            <h2 class="lead"><b>{{ $contact->name }}</b></h2>
                                            @if (!empty($contact->about))
                                                <p class="text-muted text-sm">{{ $contact->about }}</p>
                                            @endif
                                            <ul class="ml-4 mb-0 fa-ul text-muted">
                                                <li class="small"><span class="fa-li"><i class="fa fa-phone"></i></span> Тел: <a href="tel:+{{ $contact->phone }}">{{ $contact->phone_str }}</a></li>
                                            </ul>
                                        </div>
                                        <div class="card-footer">
                                            <div class="text-right">
                                                <a href="{{ route('client.chat') }}" class="btn btn-sm bg-teal">
                                                    <i class="fa fa-comments mr-2"></i>Чат
                                                </a>
                                                <a href="tel:+{{ $contact->phone }}" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-phone mr-2"></i>Позвонить
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="container-fluid">
                    Контактов ещё нет
                </div>
            @endif
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
