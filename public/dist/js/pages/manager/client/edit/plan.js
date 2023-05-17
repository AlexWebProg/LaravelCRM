// Отправка формы добавления задачи
$('#task_create_form_submit_button').click(function() {
   $('#task_create_form_submit').click();
});
$('#task_create_form').submit(function(){
    $('#modal_create_overlay').removeClass('d-none');
});

// Нажатие кнопки создания задачи
$('#addTask').click(function(){
    $('#modal_task_create .validation_error').remove();
    $('#modal_task_create .is-invalid').removeClass('is-invalid');
});

// Нажатие кнопки редактирования задачи
$('.task_edit_btn').click(function(){
    $('#modal_task_edit').modal('show');
    $('#modal_task_edit_header').text($(this).data('name'));
    $('#modal_task_edit_form_id').val($(this).data('id'));
    $('#modal_task_edit_form_name').val($(this).data('name'));
    $('#modal_task_edit_form_text').text($(this).data('text'));
    $('#modal_task_edit_form_editable').val($(this).data('editable'));

    let responsible, disabledRemember = false;
    if ($(this).data('responsible').toString().indexOf(',') > 0) {
        responsible = $(this).data('responsible').split(',');
        if (jQuery.inArray($('#logOutForm').data('id').toString(), responsible) !== -1) {
            disabledRemember = true;
        }
    } else {
        responsible = $(this).data('responsible');
        if ($('#logOutForm').data('id') === responsible) {
            disabledRemember = true;
        }
    }
    $('#modal_task_edit_form_responsible').val(responsible).trigger('change');

    if ($(this).data('remember') === 1) {
        $('#task_edit_form_remember').prop('checked',true);
    } else {
        $('#task_edit_form_remember').prop('checked',false);
    }
    if ($(this).data('closed') === 1) {
        $('#task_edit_form_closed').prop('checked',true);
    } else {
        $('#task_edit_form_closed').prop('checked',false);
    }
    if ($(this).data('editable') === 0) {
        $('#modal_task_edit_form_name, #modal_task_edit_form_text').prop('readonly',true);
        $('#modal_task_edit_form_responsible,#edit_file_input').prop('disabled',true);
        if(disabledRemember) {
            $('#task_edit_form_remember').prop('disabled', true);
        }
        if ($(this).data('completable') === 0) {
            $('#task_edit_form_closed').prop('disabled', true);
        }
    } else {
        $('#modal_task_edit_form_name, #modal_task_edit_form_text').prop('readonly',false);
        $('#modal_task_edit_form_responsible, #task_edit_form_remember, #task_edit_form_closed, #edit_file_input').prop('disabled',false);
    }
    $('#modal_task_edit .validation_error').remove();
    $('#modal_task_edit .is-invalid').removeClass('is-invalid');
});

// Отправка формы редактирования задачи
$('#task_edit_form_submit_button').click(function() {
    $('#task_edit_form_submit').click();
});
$('#task_edit_form').submit(function(){
    $('#modal_edit_overlay').removeClass('d-none');
});

// Отображение документа
$('.task_file_download_link').click(function (){
    $('#file_previewer').removeClass('d-none');
    $('#file_previewer_title').text($(this).data('name'));
    $('#file_previewer_download_btn')
        .attr('download',$(this).data('name'))
        .attr('href',$(this).data('link'));
    $('#google_doc_viewer').data('src',$(this).data('link'));
    gdv_timer_id = setInterval(loadGoogleDocViewer, 2000);
    loadGoogleDocViewer();
});
$('#file_previewer_close').click(function(){
    $('#file_previewer').addClass('d-none').append('<div class="google_doc_viewer_loading overlay dark"><i class="fa fa-spinner fa-pulse fa-3x"></i></div>');
    $('#google_doc_viewer').attr('src','');
});

// Удаление фото из fancybox
function deletePhoto(){
    swal({
        title: "Удалить файл?",
        text: "После удаления восстановить файл будет невозможно",
        icon: "warning",
        buttons: ["Нет", "Удалить"],
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                let filePath = Fancybox.getInstance().getSlide().src;
                $.ajax({
                    type:'POST',
                    url:'../task_file_delete_by_src',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {'src': filePath},
                    success:function(r){
                        switch (r.result) {
                            case 1:
                                $('button.carousel__button.fancybox__button--close').trigger('click');
                                $('.task_image_item[src="'+filePath+'"]').closest('.task_image_block').remove();
                                swal("Файл удалён", {
                                    icon: "success",
                                });
                                break;
                            default:
                                swal("При удалении файла произошла ошибка", {
                                    icon: "warning",
                                });
                                break;
                        }
                    },
                    error: function(){
                        swal("При удалении файла произошла ошибка", {
                            icon: "warning",
                        });
                    }
                });
            } else {
                swal("Файл не был удалён");
            }
        });
}

// Удаление мини-кнопкой
$('.delete_file_button').click(function(){
    let filePath = $(this).data('file'),
        item = $(this).closest('div');
    swal({
        title: "Удалить файл?",
        text: "После удаления восстановить файл будет невозможно",
        icon: "warning",
        buttons: ["Нет", "Удалить"],
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type:'POST',
                    url:'../task_file_delete_by_src',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {'src': filePath},
                    success:function(r){
                        switch (r.result) {
                            case 1:
                                item.remove();
                                swal("Файл удалён", {
                                    icon: "success",
                                });
                                break;
                            default:
                                swal("При удалении файла произошла ошибка", {
                                    icon: "warning",
                                });
                                break;
                        }
                    },
                    error: function(){
                        swal("При удалении файла произошла ошибка", {
                            icon: "warning",
                        });
                    }
                });
            } else {
                swal("Файл не был удалён");
            }
        });
});

// Удаление задачи
$('.task_delete_btn').click(function(){
    $.alert({
        type: 'blue',
        title: 'Удаление задачи',
        content: 'Действительно удалить эту задачу?<br/><hr/>',
        closeIcon: true,
        backgroundDismiss: true,
        buttons: {
            yes: {
                text: 'Удалить',
                btnClass: 'btn-primary',
                action: function(){
                    $('#task_delete_id').val($('#modal_task_edit_form_id').val());
                    $('#task_delete_form').submit();
                }
            },
            no: {
                text: 'Нет',
                btnClass: 'btn-default'
            }
        }
    });
});
$('#task_delete_form').submit(function(){
    $('#modal_edit_overlay').removeClass('d-none');
});

// Открытие списка "Просмотрено"
photo_popover = $('#tasks_list').popover({
    trigger: "click",
    sanitize: false,
    html: true,
    animation: true,
    selector: '.viewed',
    container: '#viewed_popover',
    template: '<div class="popover viewed_popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
});
