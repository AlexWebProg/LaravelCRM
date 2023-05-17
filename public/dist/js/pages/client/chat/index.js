// Чат -----------------------------------------------------
var survey_in_process = false; // Форма опроса в процессе заполнения

// Обновление чата
function updateMessageList() {
    $('#message_list').load('/chat/message_list',function(){
        scroll_to_first_un_viewed_message();
        complete_draft();
        checkNewEvents();
        starRating();
    });
}

function dynamicUpdateMessageList() {
    if (!document.hidden && !survey_in_process) {
        let updateInfo = $('#updateInfo');
        $.get("/api/checkChatUpdate",
            {
                client_id: updateInfo.data('client_id'),
                chat_updated_at: updateInfo.data('chat_updated_at'),
            },
            function (updated) {
                if (parseInt(updated, 10) === 1) {
                    if ($(window).scrollTop() + $(window).height() === $(document).height()) {
                        updateMessageList();
                    } else {
                        $('#message_list').load('/chat/message_list',function(){
                            starRating();
                        });
                    }
                }
                $('.newChatMessageBadge').text('0').addClass('d-none');
            }
        );
    }
}

// Если мы начали заполнять форму опроса, то не ищем обновления в сообщениях, чтобы не обновилась страница
$('#message_list').on('input change','form.survey input, form.survey textarea',function(){
    survey_in_process = true;
}).on('change','.rating',function () {
    survey_in_process = true;
}).on('submit','form.survey',function(evt){
    if ($(this).find('.rating_block').length) {
        $(this).find('.rating_block').each(function(){
            if ($(this).find('input.rating_required').first().val() && !$(this).find('input[type="radio"]:checked').length) {
                $(this).find('.text-danger.d-none').removeClass('d-none');
                evt.preventDefault();
            }
        });
    }
    if ($(this).find('.comment_block').length) {
        $(this).find('.comment_block').each(function(){
            if ($(this).find('input.comment_required').first().val() && !$(this).find('textarea').first().val()) {
                $(this).find('.text-danger.d-none').removeClass('d-none');
                evt.preventDefault();
            }
        });
    }
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
                starColorFull:'#17a2b8',
                starsSize: 2,
                stars: $(this).data('rating-to'),
                showInfo:false,
                inputName:$(this).data('input-name'),
            });
        });
    }
}
