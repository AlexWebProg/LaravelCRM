// Подтверждение удаления пользователя
$('.confirm_user_delete').click(function(evt){
    evt.preventDefault();
    $.alert({
        type: 'blue',
        title: 'Удаление пользователя',
        content: 'Действительно удалить этого пользователя?<br/><hr/>',
        closeIcon: true,
        backgroundDismiss: true,
        buttons: {
            yes: {
                text: 'Удалить',
                btnClass: 'btn-primary',
                action: function(){
                    $('#delete_manager_form').submit();
                }
            },
            no: {
                text: 'Нет',
                btnClass: 'btn-default'
            }
        }
    });
});

$(function () {
    $('[data-toggle="popover"]').popover({trigger: 'hover'});
});
