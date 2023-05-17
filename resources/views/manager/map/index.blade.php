@extends('manager.layouts.main')

@section('pageName')Объекты на карте@endsection

@section('navbarItem')
    <li class="nav-item d-lg-none">
        <button class="nav-link btn bg-transparent move-to-client-list">
            <i class="fa fa-th-list" aria-hidden="true"></i>
        </button>
    </li>
@endsection

@section('pageStyle')
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/map/index.css') }}">
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content" id="map_content">
            <div class="container-fluid p-0">
                <div class="row flex-column-reverse flex-lg-row m-0">
                    <div class="col-lg-3 p-0" id="client-list">
                        @if (count($clients))
                            <div class="list-group list-group-flush">
                                <div class="list-group-item p-1">
                                    <input id="search_client_in_list" type="search" class="form-control pl-3" placeholder="Поиск">
                                </div>
                                @foreach($clients as $client)
                                    <a href="javascript:void(0);" class="list-group-item list-group-item-action text-truncate client-list-item" id="client-list-item-{{ $client->id }}">{{ $client->address }}</a>
                                @endforeach
                            </div>
                        @else
                            Координаты на карте не указаны ни для одного адреса объекта
                        @endif
                    </div>
                    <div class="col-lg-9 p-0">
                        <div id="map" style="width:100%;height:calc(100vh - 50px);"></div>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('pageScript')
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script type="text/javascript">
        var myMap;
        ymaps.ready(init);
        function init () {
            myMap = new ymaps.Map('map', {
                center: [55.76, 37.64], // Москва
                zoom: 10,
                controls: ['zoomControl', 'typeSelector', 'trafficControl', 'fullscreenControl']
            });

            @if (count($clients))
                @foreach($clients as $client)
                    let myPlacemark{{$client->id}} = new ymaps.Placemark([{{$client->coordinates}}], {
                        balloonContentHeader: '{{$client->address}}',
                        balloonContent: '<p class="mt-2 mb-0">{!! $client->in_process_map_place_mark !!}</p><p class="mt-2 mb-0"><a href="{{ route('manager.client.edit', $client->id) }}"><i class="fa fa-star-o mr-2" aria-hidden="true"></i>Перейти к объекту</a></p><p class="mt-2 mb-0"><a href="tel:+{{ $client->phone }}"><i class="fa fa-phone mr-2" aria-hidden="true"></i>{{ $client->phone_str }}</a>, {{ $client->name }}</p><p class="mt-2 mb-0"><a href="https://yandex.ru/maps/?rtext=~{{$client->coordinates}}" target="_blank"><i class="fa fa-location-arrow mr-2" aria-hidden="true"></i>Построить маршрут</a></p>'
                    }, {
                        preset: '{{ $client->place_mark_preset }}',
                    });

                    myPlacemark{{$client->id}}.events.add('click', function() {
                        $(".client-list-item.list-group-item-primary").removeClass('list-group-item-primary');
                        $("#client-list-item-{{$client->id}}").addClass('list-group-item-primary');
                        $('#client-list').scrollTop(0).animate({
                            scrollTop: $("#client-list-item-{{$client->id}}").offset().top - 100
                        }, 200);
                    });

                    myPlacemark{{$client->id}}.events.add('balloonclose', function() {
                        $("#client-list-item-{{$client->id}}").removeClass('list-group-item-primary');
                    });

                    myMap.geoObjects.add(myPlacemark{{$client->id}});

                    $("#client-list-item-{{$client->id}}").click(function(){
                        myPlacemark{{$client->id}}.balloon.open();
                        $(".client-list-item.list-group-item-primary").removeClass('list-group-item-primary');
                        $(this).addClass('list-group-item-primary');
                        $('html,body').animate({
                            scrollTop: 0
                        }, 200);
                    });
                @endforeach
            @endif
        }

        // Скрытие левой панели меню
        $().ready(function(){
            $('[data-widget="pushmenu"]').PushMenu('collapse');
        });

        // Прокрутка к списку объектов на телефоне
        $('.move-to-client-list').click(function(){
            $('html,body').animate({
                scrollTop: $("#client-list").offset().top - 56
            }, 200);
        });

        // Поиск объекта
        $('#search_client_in_list').on('input', function () {
            let filter = $(this).val().toUpperCase();
            $('.client-list-item').each(function() {
                if ($(this).text().toUpperCase().indexOf(filter) > -1){
                    $(this).show();
                }else{
                    $(this).hide();
                }
            });
        });
    </script>
@endsection
