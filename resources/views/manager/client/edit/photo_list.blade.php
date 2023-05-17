<hr/>
@if (count($photos))
    <div class="row">
        @foreach ($photos as $photo)
            <div class="col-md-3 col-sm-4 col-xs-6 thumb">
                <div class="card">
                    <div class="card-body p-0">
                        <a id="{{ $photo->id }}" data-fancybox="gallery" href="{{ url('storage/'.$photo->src) }}" data-caption="{{ $photo->description }}">
                            @if (str_contains(mime_content_type(storage_path('app/public/'.$photo->src)), "video/"))
                                <video class="video-responsive" src="{{ url('storage/'.$photo->src) }}" autoplay loop muted playsinline></video>
                            @else
                                <img class="img-responsive" src="{{ url('storage/'.$photo->src) }}" alt="...">
                            @endif
                        </a>
                        <div class="p-3">
                            <p>
                                <button class="btn btn-success btn-sm" data-fancybox-trigger="gallery" data-fancybox-index="{{ $loop->index }}">
                                    <i class="fa fa-fw fa-expand" aria-hidden="true"></i>
                                </button>
                                @if (!empty($photo->popover))
                                    <button type="button" class="btn btn-primary btn-sm viewed" data-toggle="popover" data-content="{{ $photo->popover }}">
                                        <i class="fa fa-fw fa-info-circle" aria-hidden="true"></i>
                                    </button>
                                @endif
                                <button class="btn bg-orange btn-sm edit_photo_button" data-id="{{ $photo->id }}" data-comment="{{ $photo->comment }}">
                                    <i class="fa fa-fw fa-pencil-square-o text-white" aria-hidden="true"></i>
                                </button>
                                @can('delete-photo', $photo)
                                <button class="btn btn-danger btn-sm delete_photo_button" data-file="{{ url('storage/'.$photo->src) }}">
                                    <i class="fa fa-fw fa-trash-o" aria-hidden="true"></i>
                                </button>
                                @endcan
                            </p>
                            @if (!empty($photo->comment))
                                <p>{{ $photo->comment }}</p>
                            @endif
                            <p class="text-right m-0">
                                <small>{{ !empty($photo->manager) ? $photo->manager->name.', '.$photo->created_str : $photo->created_str }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div id="viewed_popover"></div>
@else
    <p class="text-center p-0 m-0">Загруженных фото нет</p>
@endif
