// Подтверждение удаления партнёра
$('.confirm_partner_delete').click(function(evt){
    evt.preventDefault();
    $.alert({
        type: 'blue',
        title: 'Удаление партнёра',
        content: 'Действительно удалить этого партнёра?<br/><hr/>',
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
