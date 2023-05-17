// Подтверждение удаления вопроса
$('.confirm_faq_delete').click(function(evt){
    evt.preventDefault();
    $.alert({
        type: 'blue',
        title: 'Удаление вопроса',
        content: 'Действительно удалить этот вопрос?<br/><hr/>',
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
