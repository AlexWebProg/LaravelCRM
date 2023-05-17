// Добавление вопроса
$('#add_question').click(function(e){
    e.preventDefault();
    $('#questions').append($('#question_template').html());
    showFormActions();
});

$('#questions').on('change','.rating_enabled',function(){
    // Возможность поставить оценку
    $(this).closest('.rating_block').find('.rating_values').toggle();
}).on('change','.comment_enabled',function() {
    // Возможность написать комментарий
    $(this).closest('.comment_block').find('.comment_values').toggle();
}).on('change','.form-check-input',function(){
    // Скрытое значение для чекбокса
    let hidden_input = $(this).closest('.form-check').find('input[type="hidden"]');
    if ($(this).is(':checked')) {
        hidden_input.prop('disabled',true);
    } else {
        hidden_input.prop('disabled',false);
    }
}).on('click','.delete_question',function(){
    // Подтверждение удаления вопроса
    let question = $(this).closest('.question');
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
                    question.remove();
                    showFormActions();
                }
            },
            no: {
                text: 'Нет',
                btnClass: 'btn-default'
            }
        }
    });
}).on('change input','input, textarea',function(){
    showFormActions();
});

// JQuery Sortable: перетягиваемые вверх-вниз строки для изменения сортировки
$(function() {
    $("#questions").sortable({
        axis: "y",
        containment: "#questions",
        cursor: "ns-resize",
        handle: ".sortable_handle",
        items: "> div.question",
        scroll: true,
        tolerance: "pointer",
        classes: {
            "ui-sortable-helper": "sorting_now"
        },
        change: function( event, ui ) {showFormActions();}
    }).disableSelection();
});

// Подтверждение удаления шаблона
$('.confirm_template_delete').click(function(e) {
    e.preventDefault();
    let form = $(this).closest('form');
    $.alert({
        type: 'blue',
        title: 'Удаление шаблона',
        content: 'Действительно удалить этот шаблон?<br/><hr/>',
        closeIcon: true,
        backgroundDismiss: true,
        buttons: {
            yes: {
                text: 'Удалить',
                btnClass: 'btn-primary',
                action: function () {
                    form.submit();
                }
            },
            no: {
                text: 'Нет',
                btnClass: 'btn-default'
            }
        }
    });
});
