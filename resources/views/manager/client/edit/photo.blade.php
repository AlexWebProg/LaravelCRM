@extends('manager.client.edit.layout')

@section('pageStyle')
    @parent
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/manager/client/edit/photo.css') }}">
    <link rel="stylesheet" href="{{ assetVersioned('dist/css/pages/common/photo_list.css') }}">
@endsection

@section('action_type_section')
    <form method="post" action="{{ route('manager.client.photo.upload',$client->id) }}" enctype="multipart/form-data"
          class="dropzone rounded bg-light" id="uploadPhotoForm">
        <div class="dz-message" data-dz-message>
            <h4><i class="fa fa-camera mr-2" aria-hidden="true"></i>Загрузить фото или видео</h4>
            <p>Перетяните фото или видео mp4 в эту область или кликните для их выбора</p>
        </div>
        @csrf
        @method('put')
    </form>

    <div id="photo_list"></div>

    <div class="modal fade" id="modal_edit">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Комментарий к фото</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('manager.client.photo.update',$client->id) }}">
                        @csrf
                        @method('patch')
                        <input id="edit_form_id" type="hidden" name="id" value="">
                        @error('id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="form-group">
                            <label>Комментарий</label>
                            <textarea id="edit_form_comment" class="form-control @error('comment') is-invalid @enderror" rows="3" name="comment" placeholder="Ваш комментарий к фото ...">{{ old('comment') }}</textarea>
                            @error('comment')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <input id="modal_edit_form_submit" type="submit" class="d-none">
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle-o mr-2" aria-hidden="true"></i>Отмена</button>
                    <button type="button" class="btn btn-primary" onclick="$('#modal_edit_form_submit').click();"><i class="fa fa-check mr-2" aria-hidden="true"></i>Сохранить</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
@endsection

@section('pageScript')
    @parent
    <script src="{{ assetVersioned('dist/js/pages/manager/client/edit/photo.js') }}"></script>
    @if($errors->has('photo_update'))
        <script>$('#modal_edit').modal('show');</script>
    @endif
@endsection
