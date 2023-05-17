// Отображение документа
$(document).on('click','.message_file_download_link',function (){
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

// Удаление файла мини-кнопкой
$('.delete_file_button').click(function(e){
    e.stopPropagation();
    let filePath = $(this).data('file'),
        genMesId = $(this).data('gen_mes'),
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
                    url:'../file_delete_by_src',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {'src': filePath, 'gen_mes_id': genMesId},
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

// Подтверждение удаления сообщения из истории
$('.confirm_message_delete').click(function(evt){
    evt.preventDefault();
    let currentForm = $(this).parent();
    $.alert({
        type: 'blue',
        title: 'Удаление документа',
        content: 'Действительно удалить это сообщение из всех чатов?<br/><hr/>',
        closeIcon: true,
        backgroundDismiss: true,
        buttons: {
            yes: {
                text: 'Удалить',
                btnClass: 'btn-primary',
                action: function(){
                    currentForm.submit();
                }
            },
            no: {
                text: 'Нет',
                btnClass: 'btn-default'
            }
        }
    });
});
