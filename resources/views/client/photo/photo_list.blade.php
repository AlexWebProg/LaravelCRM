@if (count($photos))
    <div class="row">
        @foreach ($photos as $photo)
            <div class="col-md-3 col-sm-4 col-xs-6 thumb">
                <div class="card">
                    <div class="card-body p-0">
                        <a data-fancybox="gallery" href="{{ url('storage/'.$photo->src) }}" data-caption="{{ $photo->description }}">
                            @if (str_contains(mime_content_type(storage_path('app/public/'.$photo->src)), "video/"))
                                <video class="video-responsive" src="{{ url('storage/'.$photo->src) }}" autoplay loop muted playsinline></video>
                            @else
                                <img class="img-responsive" src="{{ url('storage/'.$photo->src) }}" alt="...">
                            @endif
                        </a>
                        <div class="p-3">
                            @if (!empty($photo->comment))
                                <p>{{ $photo->comment }}</p>
                            @endif
                            <p class="text-right m-0">
                                <small>{{ $photo->created_str }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    Фото Вашего объекта ещё нет
@endif
