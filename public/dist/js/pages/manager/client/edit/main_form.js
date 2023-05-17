// Удаление объекта -----------------------------------------------------
// Подтверждение удаления объекта
$('.confirm_object_delete').click(function(evt){
    evt.preventDefault();
    $.alert({
        type: 'blue',
        title: 'Удаление объекта',
        content: 'Действительно удалить этот объект?<br/><hr/>',
        closeIcon: true,
        backgroundDismiss: true,
        buttons: {
            yes: {
                text: 'Удалить',
                btnClass: 'btn-primary',
                action: function(){
                    $('#delete_form_submit').click();
                }
            },
            no: {
                text: 'Нет',
                btnClass: 'btn-default'
            }
        }
    });
});

// Скрытие-отображение полей при изменении статуса
$('#ob_status').change(function(){
    let val = $(this).val(),
        in_process_block = $('#in_process_block'),
        warranty_end_block = $('#warranty_end_block');
    if (val === '1') {
        in_process_block.removeClass('d-none');
    } else {
        in_process_block.addClass('d-none');
    }
    if (val === '2') {
        warranty_end_block.removeClass('d-none');
        $('#warranty_end').click();
    } else {
        warranty_end_block.addClass('d-none');
    }
});

// На гарантии до
$().ready(function () {
    $('#warranty_end').datetimepicker({
        locale: 'ru',
        format: 'DD.MM.YYYY'
    });
});
