// Загрузка файла
$(function () {
    bsCustomFileInput.init();
});

// Редактирование документа
$('.edit_file').click(function(){
    $('#modal_edit').modal('show');
    $('#modal_edit_title').text($(this).data('name'));
    $('#edit_form_id').val($(this).data('id'));
    $('#edit_form_name').val($(this).data('name'));
    $('#edit_form_comment').text($(this).data('comment'));
});

// Удаление документа -----------------------------------------------------
// Подтверждение удаления документа
$('.confirm_tech_doc_delete').click(function(evt){
    evt.preventDefault();
    let currentForm = $(this).parent();
    $.alert({
        type: 'blue',
        title: 'Удаление документа',
        content: 'Действительно удалить этот документ?<br/><hr/>',
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

// Загрузка документа
$('#modal_create_form_submit_button').click(function(){
    $('#modal_create_form_submit').click();
});
$('#modal_create_form').submit(function(){
    $('#modal_create_overlay').removeClass('d-none');
});

// Редактирование документа
$('#modal_edit_form_submit_button').click(function(){
    $('#modal_edit_form_submit').click();
});

// Скрытие информации о тех документе при развороте на весь экран
$('button.btn-tool[data-card-widget="maximize"]').click(function(){
    $(this).closest('div.card').find('div.tech_doc_info').toggle();
});
