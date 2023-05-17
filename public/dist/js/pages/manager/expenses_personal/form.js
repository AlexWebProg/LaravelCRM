// Показываем или скрываем поле "Передача: кому"
function toggleTransferToBlock() {
    let transfer_to_block = $('#transfer_to_block');
    if (parseInt($('#expense_category').val(),10) === 4 && transfer_to_block.hasClass('d-none')) {
        transfer_to_block.removeClass('d-none');
    } else if (!transfer_to_block.hasClass('d-none')) {
        transfer_to_block.addClass('d-none');
    }
}

$('#expense_category').change(function(){
    toggleTransferToBlock();
});

$().ready(function () {
    // Дата
    $('#date').datetimepicker({
        locale: 'ru',
        format: 'DD.MM.YYYY'
    });
    toggleTransferToBlock();
    formatInputSum($('#sum'));
});

// Формат суммы
$('#sum').bind('input change',function(){
    formatInputSum($(this));
});

// Подтверждение удаления расхода
$('.confirm_expense_delete').click(function(evt){
    evt.preventDefault();
    let clicked_item = $(this),
        currentForm = clicked_item.parent();
    $.alert({
        type: 'blue',
        title: 'Удаление расхода или прихода',
        content: 'Удалить этот расход или приход?<br/><hr/>',
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
