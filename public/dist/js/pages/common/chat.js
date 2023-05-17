// Чат - Общие функции ----------------------------------------------------
let draft_completed = 0;

// Проверка обновлений чата
$().ready(function(){
    updateMessageList();
    setInterval(dynamicUpdateMessageList, 3000);
});

// Открытие списка функций сообщения
$('#message_list').popover({
    trigger: "click",
    sanitize: false,
    html: true,
    animation: true,
    selector: '.functions',
    container: '#functions_popover',
});

// Копирование текста сообщения в буфер
$(document).on('click','#functions_popover .copy_message',function (){
    let mesId = $(this).closest('ul').data('message_id'),
        message = $('#message'+mesId),
        text = message.data('text'),
        functions = message.find('button.functions');
    navigator.clipboard.writeText(text);
    $(document).Toasts('create', {
        class: 'bg-success',
        icon: 'fa fa-check fa-lg',
        title: 'Сообщение скопировано',
        autohide: true
    });
    functions.popover('hide');
});

// Ответить на сообщение
$(document).on('click','#functions_popover .reply_message',function (){
    let mesId = $(this).closest('ul').data('message_id'),
        message = $('#message'+mesId),
        text = message.data('text'),
        author = message.data('author'),
        functions = message.find('button.functions'),
        chat_text = $('#chat_text'),
        chat_text_entered = chat_text.val();
    unset_edit_state();
    $('#reply_message_block_text').html(text);
    $('#reply_message_block_author').html(author);
    $('#reply_message_block').slideDown(200);
    $('#reply_message_id').val(mesId);
    chat_text.val(chat_text_entered).focus();
    functions.popover('hide');
    update_draft();
});
$('#reply_message_cancel').click(function(){
    unset_reply_state();
    update_draft();
});
// Отменяет состояние ответа на сообщение
function unset_reply_state(){
    $('#reply_message_block_text').text('');
    $('#reply_message_block_author').text('');
    $('#reply_message_block').slideUp(200);
    $('#reply_message_id').val('');
}
$(document).on('click','#message_list .replied_message',function (){
    let replied_message = $('#message'+$(this).data('replied'));
    $('html,body').animate({
        scrollTop: replied_message.offset().top - 175
    }, 200);
    setTimeout(function(){
        replied_message.addClass("highlighted").delay(300).queue(function(){
            $(this).removeClass("highlighted").dequeue();
        });
    },300);
});

// Редактирование сообщения
$(document).on('click','#functions_popover .edit_message',function () {
    let mesId = $(this).closest('ul').data('message_id'),
        message = $('#message'+mesId),
        text = message.data('text'),
        functions = message.find('button.functions');
    if (text !== '') {
        unset_reply_state();

        $('#files_preview_block').text('').slideUp(200);
        $('#add_message_files').val('');
        $('#add_message_loading_block').addClass('d-none');

        $('#chat_text').val(text).focus();
        $('#edit_message_block_text').html(text);
        $('#edit_message_block').slideDown(200);
        $('#edit_message_id').val(mesId);

        functions.popover('hide');
        update_draft();
    }
});
$('#edit_message_cancel').click(function(){
    unset_edit_state();
    update_draft();
});
// Отменяет состояние редактирования сообщения
function unset_edit_state(){
    $('#edit_message_block_text').text('');
    $('#edit_message_block').slideUp(200);
    $('#edit_message_id').val('');
    $('#chat_text').val('').focus();
}

// Удаление сообщения
$(document).on('click','#functions_popover .delete_message',function (){
    $('#delete_message_id').val($(this).closest('ul').data('message_id'));
    $.alert({
        type: 'blue',
        title: 'Удаление сообщения',
        content: 'Действительно удалить сообщение?<br/><hr/>',
        closeIcon: true,
        backgroundDismiss: true,
        buttons: {
            yes: {
                text: 'Удалить',
                btnClass: 'btn-primary',
                action: function(){
                    $('#delete_message').submit();
                }
            },
            no: {
                text: 'Нет',
                btnClass: 'btn-default'
            }
        }
    });
});
$('#delete_message').submit(function(evt){
    evt.preventDefault();
    let form = $(this),
        message_id = $('#delete_message_id').val();
    if (message_id === '') {
        $(document).Toasts('create', {
            class: 'bg-danger',
            icon: 'fa fa-exclamation-triangle fa-lg',
            title: 'Сообщение не удалено',
            body: 'При удалении сообщения произошла ошибка'
        });
        updateMessageList();
    } else {
        $.ajax({
            type:'POST',
            url: form.attr('action'),
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: form.serialize(),
            success:function(){
                updateMessageList();
            },
            error: function(){
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    icon: 'fa fa-exclamation-triangle fa-lg',
                    title: 'Сообщение не удалено',
                    body: 'При удалении сообщения произошла ошибка'
                });
                updateMessageList();
            }
        });
    }
});

// Отправка сообщения
$('#add_message').submit(function(evt){
    evt.preventDefault();
    let form = $(this),
        input = $('#chat_text'),
        filesUploading = false;
    if ($('#add_message_files').val() !== '') filesUploading = true;
    if (input.val() === '' && !filesUploading) {
        input.focus();
    } else {
        $('#add_message_button').prop('disabled',true);
        if (filesUploading) $('#add_message_loading_block').removeClass('d-none');
        $.ajax({
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            url: form.attr('action'),
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: new FormData(document.getElementById('add_message')),
            success:function(){
                unset_reply_state();
                unset_edit_state();
                $('#chat_text').val('');
                updateMessageList();
                if (filesUploading) {
                    $('#files_preview_block').text('').slideUp(200);
                    $('#add_message_files').val('');
                    $('#add_message_loading_block').addClass('d-none');
                }
                $('#add_message_button').prop('disabled',false);
                update_draft();
            },
            error: function(){
                let edit_message_id = $('#edit_message_id').val();
                $(document).Toasts('create', {
                    class: 'bg-danger',
                    icon: 'fa fa-exclamation-triangle fa-lg',
                    title: (edit_message_id === '') ? 'Сообщение не отправлено' : 'Сообщение не изменено',
                    body: (edit_message_id === '') ? 'При отправке сообщения произошла ошибка' : 'Это сообщение уже нельзя изменить'
                });
                $('#add_message_button').prop('disabled',false);
                if (filesUploading) $('#add_message_loading_block').addClass('d-none');
            }
        });
    }
});

// Кнопка отправки сообщения для демо-объекта
$('#demo_message_add').click(function(evt){
    evt.preventDefault();
});

// Прокрутка вниз чата
$('#scroll_to_bottom_btn').click(function(){
    $("html, body").animate({ scrollTop: $(document).height() }, 500);
});
$(window).on('scroll', function () {
    if($(window).scrollTop() + $(window).height() < $(document).height()) {
        $('#scroll_to_bottom_div').stop(true).animate({
            opacity: 0.5
        }, 20);
    } else {
        $('#scroll_to_bottom_div').stop(true).animate({
            opacity: 0
        }, 20);
    }
});

// Отображение документа
$(document).on('click','#message_list .message_file_download_link',function (){
    $('#file_previewer').removeClass('d-none');
    $('#file_previewer_title').text($(this).data('name'));
    $('#file_previewer_download_btn')
        .attr('download',$(this).data('name'))
        .attr('href',$(this).data('link'));
    $('#google_doc_viewer').data('src',$(this).data('link'));
    gdv_timer_id = setInterval(loadGoogleDocViewer, 2000);
    loadGoogleDocViewer();
});
$('#file_previewer_close').click(function(){
    $('#file_previewer').addClass('d-none').append('<div class="google_doc_viewer_loading overlay dark"><i class="fa fa-spinner fa-pulse fa-3x"></i></div>');
    $('#google_doc_viewer').attr('src','');
});

// Прокрутка к первому непрочитанному сообщению, или вниз страницы, если его нет
function scroll_to_first_un_viewed_message(){
    let un_viewed = $('.un_viewed');
    if (un_viewed.length) {
        $("html, body").animate({ scrollTop: un_viewed.first().offset().top - 70 }, 500);
    } else {
        $("html, body").animate({ scrollTop: $(document).height() }, 500);
    }
}

// Сохранение черновика
let typingTimer;
$('#chat_text').bind('input change',function() {
    clearTimeout(typingTimer);
    if ($('#chat_draft_message_text').val() !== $(this).val()) {
        typingTimer = setTimeout(update_draft, 500);
    }
});
function update_draft(){
    // Для демо-объекта черновик не сохраняем
    if(!$('#demo_message_add').length) {
        $('#chat_draft_message_text').val($('#chat_text').val());
        $('#chat_draft_reply_message_id').val($('#reply_message_id').val());
        $('#chat_draft_edit_message_id').val($('#edit_message_id').val());
        $.post("/api/storeChatDraft",$('#set_chat_draft').serialize());
    }
}

// Заполнение черновика
function complete_draft(){
    if (draft_completed === 0) {
        let reply_message_id = $('#reply_message_id').val(),
            edit_message_id = $('#edit_message_id').val();
        if (reply_message_id !== '') {
            let message = $('#message'+reply_message_id),
                text = message.data('text'),
                author = message.data('author');
            $('#reply_message_block_text').html(text);
            $('#reply_message_block_author').html(author);
            $('#reply_message_block').show();
        } else if (edit_message_id !== '') {
            let message = $('#message'+edit_message_id),
                text = message.data('text');
            if (text !== '') {
                $('#edit_message_block_text').html(text);
                $('#edit_message_block').show();
            }
        }
        draft_completed = 1;
    }
}
