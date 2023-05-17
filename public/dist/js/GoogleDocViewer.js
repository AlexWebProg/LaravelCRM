// Просмотр PDF в GoogleDocViewer
let google_doc_viewer = $('.google_doc_viewer'),
    gdv_timer_id;

function loadGoogleDocViewer() {
    let loadComplete = 1;
    google_doc_viewer.each(function(){
        if ($(this).data('src') !== '') {
            $(this).attr('src','https://docs.google.com/viewer?url=' + $(this).data('src') + '&embedded=true');
            loadComplete = 0;
        }
    });
    if (loadComplete === 1) clearInterval(gdv_timer_id);
}

$().ready(function() {
    if (google_doc_viewer.length) {
        gdv_timer_id = setInterval(loadGoogleDocViewer, 2000);
        loadGoogleDocViewer();
    }
});

google_doc_viewer.on('load', function() {
    if (this.contentWindow.length) {
        $(this).closest('div.card').find('.google_doc_viewer_loading').remove();
        $(this).data('src','');
    }
});
