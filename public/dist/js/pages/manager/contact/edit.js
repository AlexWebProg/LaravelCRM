// Подтверждение удаления контакта
$('.confirm_contact_delete').click(function(evt){
    evt.preventDefault();
    $.alert({
        type: 'blue',
        title: 'Удаление контакта',
        content: 'Действительно удалить этот контакт?<br/><hr/>',
        closeIcon: true,
        backgroundDismiss: true,
        buttons: {
            yes: {
                text: 'Удалить',
                btnClass: 'btn-primary',
                action: function(){
                    $('#delete_form').submit();
                }
            },
            no: {
                text: 'Нет',
                btnClass: 'btn-default'
            }
        }
    });
});
