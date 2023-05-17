// Фото -----------------------------------------------------
let photo_list = $('#photo_list');
function showPhotoList() {
    photo_list.load('../photo_list',checkNewEvents());
}

$().ready(function(){
    showPhotoList();
});

// Загрузка фото
let myDropzone = new Dropzone("#uploadPhotoForm", {
    dictCancelUpload: "Отменить загрузку",
    dictRemoveFile: "Удалить",
    dictFileTooBig: "Файл слишком большой ({{filesize}}MiB). Максимальный размер фото: {{maxFilesize}}MiB.",
    dictInvalidFileType: "Формат файла не поддерживается. Можно загружать только фото и видео в форматах mp4 или mov",
    acceptedFiles: 'image/*, video/mp4, video/quicktime',
    maxFilesize: 100,
    addRemoveLinks: true,
    timeout: 150000,
});

myDropzone.on("success", function(file) {
    myDropzone.removeFile(file);
    $(document).Toasts('create', {
        class: 'bg-success',
        icon: 'fa fa-check fa-lg',
        title: 'Фото загружено',
        autohide: true
    });
    showPhotoList();
});

myDropzone.on("error", function(file, response) {
    $(file.previewElement).addClass("dz-error").find('.dz-error-message').text(response.message);
    $(document).Toasts('create', {
        class: 'bg-danger',
        icon: 'fa fa-exclamation-triangle fa-lg',
        title: 'Фото не загружено',
        body: 'Не загруженное фото осталось в списке загружаемых. Можно увидеть причину, кликнув по нему. Удалите его из списка, чтобы загрузить новые фото'
    });
});

// Удаление фото из fancybox
function deletePhoto(){
    swal({
        title: "Удалить фото?",
        text: "После удаления восстановить фото будет невозможно",
        icon: "warning",
        buttons: ["Нет", "Удалить"],
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                let filePath = Fancybox.getInstance().getSlide().src;
                $.ajax({
                    type:'POST',
                    url:'../photo_delete_by_src',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {'src': filePath},
                    success:function(r){
                        switch (r.result) {
                            case 1:
                                $('button.carousel__button.fancybox__button--close').trigger('click');
                                showPhotoList();
                                swal("Фото удалено", {
                                    icon: "success",
                                });
                                break;
                            default:
                                swal("При удалении фото произошла ошибка", {
                                    icon: "warning",
                                });
                                break;
                        }
                    },
                    error: function(){
                        swal("При удалении фото произошла ошибка", {
                            icon: "warning",
                        });
                    }
                });
            } else {
                swal("Фото не было удалено.");
            }
        });
}

// Редактирование описания
photo_list.on('click','.edit_photo_button',function(){
    $('#modal_edit').modal('show');
    $('#edit_form_id').val($(this).data('id'));
    $('#edit_form_comment').text($(this).data('comment'));
});

// Удаление мини-кнопкой
photo_list.on('click','.delete_photo_button',function(){
    let filePath = $(this).data('file');
    swal({
        title: "Удалить фото?",
        text: "После удаления восстановить фото будет невозможно",
        icon: "warning",
        buttons: ["Нет", "Удалить"],
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type:'POST',
                    url:'../photo_delete_by_src',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {'src': filePath},
                    success:function(r){
                        switch (r.result) {
                            case 1:
                                showPhotoList();
                                swal("Фото удалено", {
                                    icon: "success",
                                });
                                break;
                            default:
                                swal("При удалении фото произошла ошибка", {
                                    icon: "warning",
                                });
                                break;
                        }
                    },
                    error: function(){
                        swal("При удалении фото произошла ошибка", {
                            icon: "warning",
                        });
                    }
                });
            } else {
                swal("Фото не было удалено.");
            }
        });
});

// Открытие списка "Просмотрено"
photo_popover = photo_list.popover({
    trigger: "click",
    sanitize: false,
    html: true,
    animation: true,
    selector: '.viewed',
    container: '#viewed_popover',
    template: '<div class="popover viewed_popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
});
