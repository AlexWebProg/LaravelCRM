// Запись клика в статистику
$('a[data-statclick], .exit_button[data-statclick], #modal_webcam_warning_link').click(function(){
    $.get( "/stat/" + $(this).data('statclick'));
});

// Переход к началу страницы
$('.moveToTop').click(function(){
    $("html, body").animate({ scrollTop: 0 }, 500);
});

// serviceWorker for PWA
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register("/serviceWorker.js", {
        scope: '/'
    });
}

// Проверяет новые события (новые сообщения в чате и тд)
function checkNewEvents(){
    if (!document.hidden) {
        $.get("/api/client/checkNewEvents",
            {
                id: $('#logOutForm').data('id'),
            },
            function( data ) {
                if (data.intNewChatMessages) {
                    $('.newChatMessageBadge').text(data.intNewChatMessages).removeClass('d-none');
                } else {
                    $('.newChatMessageBadge').text('0').addClass('d-none');
                }
                if (data.intNewPhoto) {
                    $('.newPhotoBadge').text(data.intNewPhoto).removeClass('d-none');
                } else {
                    $('.newPhotoBadge').text('0').addClass('d-none');
                }
                if (data.intNewEstimate) {
                    $('.newEstimateBadge').text(data.intNewEstimate).removeClass('d-none');
                } else {
                    $('.newEstimateBadge').text('0').addClass('d-none');
                }
                if (data.intNewTechDoc) {
                    $('.newTechDocBadge').text(data.intNewTechDoc).removeClass('d-none');
                } else {
                    $('.newTechDocBadge').text('0').addClass('d-none');
                }
                if (data.intPlanUpdated) {
                    $('.newPlanBadge').text(data.intPlanUpdated).removeClass('d-none');
                } else {
                    $('.newPlanBadge').text('0').addClass('d-none');
                }
        });
    }
}

$().ready(function() {
    checkNewEvents();
    setInterval(checkNewEvents, 10000);
});

// Условия просмотра вебкамеры
$('.webcam_link').click(function (){
    $('#modal_webcam_warning').modal('show');
});
$('#modal_webcam_warning_checkbox').change(function(){
    if ($(this).is(':checked')) {
        $('#modal_webcam_warning_link').prop('disabled',false);
    } else {
        $('#modal_webcam_warning_link').prop('disabled',true);
    }
});

// Закрытие Popover при клике за его пределами
$('body').on('click', function (e) {
    $('[data-toggle=popover]').each(function () {
        // hide any open popovers when the anywhere else in the body is clicked
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
});

// Автоматическое скрытие оповещений об успехе
$(document).ready(function () {
    setTimeout(function(){
        $('.toast.bg-success').toast('hide')
    },2000);
});
