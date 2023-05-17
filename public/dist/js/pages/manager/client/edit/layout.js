// Проверяет новые события (новые сообщения в чате и тд)
function checkNewEvents(){
    if (!document.hidden) {
        $.get("/api/manager/checkNewEventsClient",
            {
                manager_id: $('#logOutForm').data('id'),
                client_id: $('#client_edit_form').data('client_id'),
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
                if (data.intNewMasterEstimate) {
                    $('.newMasterEstimateBadge').text(data.intNewMasterEstimate).removeClass('d-none');
                } else {
                    $('.newMasterEstimateBadge').text('0').addClass('d-none');
                }
                if (data.intNewTechDoc) {
                    $('.newTechDocBadge').text(data.intNewTechDoc).removeClass('d-none');
                } else {
                    $('.newTechDocBadge').text('0').addClass('d-none');
                }
                if (data.intNewActiveTasks) {
                    $('.newActiveTasksBadge').text(data.intNewActiveTasks).removeClass('d-none');
                } else {
                    $('.newActiveTasksBadge').text('0').addClass('d-none');
                }
            });
    }
}

$().ready(function() {
    checkNewEvents();
    setInterval(checkNewEvents, 10000);
});
