@extends('manager.client.edit.layout')

@section('pageStyle')
    @parent
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/client/edit/estimate.css') }}">
@endsection

@section('action_type_section')
    <form method="post" action="{{ route('manager.client.estimate.upload',$client->id) }}" enctype="multipart/form-data"
          class="dropzone rounded bg-light" id="uploadEstimateForm">
        <div class="dz-message" data-dz-message>
            <h4><i class="fa fa-file-pdf-o mr-2" aria-hidden="true"></i>Загрузить смету</h4>
            <p>Перетяните смету в формате PDF в эту область или кликните для выбора файла</p>
        </div>
        @csrf
        @method('put')
    </form>
    <form id="main_form" action="{{ route('manager.client.estimate_comment.update', $client->id) }}" method="post">
        @csrf
        @method('patch')
        <div class="form-group mt-3 mb-0">
            <label>Комментарии</label>
            @if (!empty($client->estimate_comment) && !empty($client->estimate_comment->popover))
                <button type="button" class="btn bg-transparent p-0 mb-1 text-info btn-sm viewed" data-toggle="popover" data-content="{{ $client->estimate_comment->popover }}">
                    <i class="fa fa-fw fa-info-circle" aria-hidden="true"></i>
                </button>
            @endif
            <textarea class="form-control @error('estimate_comment') is-invalid @enderror" rows="6" id="estimate_comment" name="estimate_comment" placeholder="Комментарии к смете">{{ old('estimate_comment', $client->estimate_comment?->text) }}</textarea>
            @error('estimate_comment')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <input id="main_form_submit" type="submit" class="d-none">
    </form>
    <p class="text-right m-0">
        <small class="text-muted">{{ !empty($client->estimate_comment) && !empty($client->estimate_comment->manager) ? 'обновил '.$client->estimate_comment->manager->name.' '.$client->estimate_comment->updated_str : 'Данных по обновлению пока нет' }}</small>
    </p>
    <hr/>

    @if (count($client->estimate))
        <div id="estimate_list">
            @foreach ($client->estimate as $estimate)
                <div class="card {{ empty($loop->index) ? 'card-primary' : 'card-secondary' }}">
                    <div class="card-header long-text">
                        <h3 class="card-title">{{ empty($loop->index) ? 'Актуальная смета' : 'Смета' }} от {{ $estimate->created_str }}</h3>
                        <div class="card-tools">
                            @if (!empty($estimate->popover))
                                <button type="button" class="btn btn-tool viewed" data-toggle="popover" data-content="{{ $estimate->popover }}">
                                    <i class="fa fa-info-circle" aria-hidden="true"></i>
                                </button>
                            @endif
                            @can('edit_estimate')
                            <form action="{{ route('manager.client.estimate.delete', [$client->id, $estimate->id]) }}" method="post" class="d-inline">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-tool deleteEstimate">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                            @endcan
                            <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                <i class="fa fa-expand"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body file_embed_card_body">
                        <iframe class="google_doc_viewer file_embed_iframe" src="" data-src="{{ url('storage/'.$estimate->src) }}"></iframe>
                    </div>
                    <!-- /.card-body -->
                    <div class="google_doc_viewer_loading overlay dark">
                        <i class="fa fa-spinner fa-pulse fa-3x"></i>
                    </div>
                    @if (!empty($estimate->manager))
                        <div class="card-footer text-right">
                            <small>{{ $estimate->manager->name }}, {{ $estimate->created_str }}</small>
                        </div>
                    @endif
                </div>
            @endforeach
            <div id="viewed_popover"></div>
        </div>
    @else
        <p class="text-center p-0 m-0">Смета не загружена</p>
    @endif
@endsection

@section('pageScript')
    @parent
    <script src="{{ assetVersioned('dist/js/pages/manager/client/edit/estimate.js') }}"></script>
@endsection
