// Подтверждение перехода в карточку объекта
$('.answer_client_href').click(function(){
    let link = $(this).data('link');
    $.alert({
        type: 'blue',
        title: 'Переход к объекту',
        content: 'Перейти в карточку объекта?<br/><hr/>',
        closeIcon: true,
        backgroundDismiss: true,
        buttons: {
            yes: {
                text: 'Перейти',
                btnClass: 'btn-primary',
                action: function(){
                    location.href = link;
                }
            },
            no: {
                text: 'Нет',
                btnClass: 'btn-default'
            }
        }
    });
});

// Сортировка ответов
$('.answers_sort').change(function (){
    let list = $(this).closest('.modal-body').find('.answers').first(),
        elements = list.find('.answer');
    switch ($(this).val()){
        case 'created_desc':
            elements
                .sort((a,b) => $(b).data("created") - $(a).data("created"))
                .appendTo(list);
            break;
        case 'created_asc':
            elements
                .sort((a,b) => $(a).data("created") - $(b).data("created"))
                .appendTo(list);
            break;
        case 'rating_asc':
            elements
                .sort((a,b) => $(a).data("rating") - $(b).data("rating"))
                .appendTo(list);
            break;
        case 'rating_desc':
            elements
                .sort((a,b) => $(b).data("rating") - $(a).data("rating"))
                .appendTo(list);
            break;
        case 'address_asc':
            elements
                .sort((a,b) => ($(a).data("address") > $(b).data("address")) ? 1 : -1)
                .appendTo(list);
            break;
    }
});

// Подтверждение удаления опроса
$('.confirm_survey_delete').click(function(e) {
    e.preventDefault();
    let form = $(this).closest('form');
    $.alert({
        type: 'blue',
        title: 'Удаление опроса',
        content: 'Действительно удалить этот опрос?<br/>При этом будут удалены сообщения с этим опросом в чатах участников и результаты опроса - эта страница.<br/><hr/>',
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
