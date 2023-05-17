$().ready(function () {
    // Дата
    $('#date').datetimepicker({
        locale: 'ru',
        format: 'DD.MM.YYYY'
    });
    formatInputSum($('#chk_amount'));
    formatInputSum($('#garb_amount'));
    formatInputSum($('#tool_amount'));
    formatInputSum($('#received_sum'));
});

// Формат суммы
$('#chk_amount,#garb_amount,#tool_amount,#received_sum').bind('input change',function(){
    formatInputSum($(this));
});

// Подтверждение удаления расхода
$('.confirm_expense_delete').click(function(evt){
    evt.preventDefault();
    let clicked_item = $(this),
        currentForm = clicked_item.parent();
    $.alert({
        type: 'blue',
        title: 'Удаление расхода',
        content: 'Удалить расход?<br/><hr/>',
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
