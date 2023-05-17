// Чат -----------------------------------------------------
// Обновление чата
function updateMessageList() {
    $('#message_list').load('../chat_message_list',function(){
        complete_draft();
        scroll_to_first_un_viewed_message();
        checkNewEvents();
        starRating();
    });
}

function dynamicUpdateMessageList() {
    let updateInfo = $('#updateInfo');
    $.get("/api/checkChatUpdate",
        {
            client_id: updateInfo.data('client_id'),
            chat_updated_at: updateInfo.data('chat_updated_at'),
        },
        function(updated) {
            if (parseInt(updated,10) === 1) {
                if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 2) {
                    updateMessageList();
                } else {
                    $('#message_list').load('../chat_message_list',function(){
                        starRating();
                    });
                }
            }
        }
    );
}

// Информация о просмотрах сообщения
$(document).on('click','#functions_popover .viewed',function (){
    let mesId = $(this).closest('ul').data('message_id'),
        message = $('#message'+mesId),
        viewed_container = message.find('span.viewed_container'),
        functions = message.find('button.functions');
    functions.popover('hide');
    viewed_container.popover({
        sanitize: false,
        html: true,
        animation: true,
        trigger: 'manual',
        content: viewed_container.data('viewed'),
        container: '#functions_popover',
        template: '<div class="popover viewed_popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
    });
    viewed_container.popover('show');
});

// Рейтинг
function starRating(){
    let ratingBlocks = $('#message_list').find('.rating');
    if (ratingBlocks.length) {
        ratingBlocks.each(function(){
            $(this).starRating({
                starIconEmpty:'fa fa-star-o',
                starIconFull:'fa fa-star',
                starColorEmpty:'white',
                starColorFull:'#ffc107',
                starsSize: 2,
                stars: $(this).data('rating-to'),
                showInfo:false,
                inputName:$(this).data('input-name'),
            });
        });
    }
}
