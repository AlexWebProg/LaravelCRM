@extends('manager.client.edit.layout')

@section('action_type_section')
    <form id="main_form" action="{{ route('manager.client.webcam', $client->id) }}" method="post">
        @csrf
        @method('patch')
            <div class="form-group">
                <label>Веб-камера</label>
                <div class="input-group">
                    <input id="webCamHref" type="text" class="form-control" name="webcam" placeholder="Ссылка на веб-камеру"
                           value="{{ old('webcam', $client->webcam) }}" @cannot('edit_webcam')readonly="readonly"@endcannot>
                    <div class="input-group-append">
                        <button id="webCamView" class="btn btn-outline-secondary" title="Открыть ссылку в новой вкладке" type="button"><i class="fa fa-external-link" aria-hidden="true"></i></button>
                    </div>
                </div>
                @error('webcam')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        <input type="hidden" name="id" value="{{ $client->id }}"/>
        <input id="main_form_submit" type="submit" class="d-none">
    </form>
@endsection

@section('pageScript')
    @parent
    <script src="{{ assetVersioned('dist/js/pages/manager/client/edit/webcam.js') }}"></script>
@endsection
