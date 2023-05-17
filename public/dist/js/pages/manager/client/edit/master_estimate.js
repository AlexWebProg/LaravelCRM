let myDropzone = new Dropzone("#uploadEstimateForm", {
    dictRemoveFile: "Удалить",
    dictFileTooBig: "Файл слишком большой ({{filesize}}MiB). Максимальный размер файла: {{maxFilesize}}MiB.",
    dictInvalidFileType: "Формат файла не поддерживается. Можно загружать файлы в формате PDF",
    acceptedFiles: '.pdf',
    maxFilesize: 20,
    maxFiles: 1,
    addRemoveLinks: true,
    timeout: 50000,
});

myDropzone.on("success", function(file) {
    location.reload();
});

myDropzone.on("error", function(file, response) {
    $(file.previewElement).addClass("dz-error").find('.dz-error-message').text(response.message);
    $(document).Toasts('create', {
        class: 'bg-danger',
        icon: 'fa fa-exclamation-triangle fa-lg',
        title: 'Файл не загружен',
        body: 'Не загруженный файл находится в окне загрузки. Можно увидеть причину, кликнув по нему. Удалите его из окна, чтобы загрузить новый'
    });
});

// Удаление сметы
$('.deleteEstimate').click(function(evt){
    evt.preventDefault();
    let currentForm = $(this).parent();
    $.alert({
        type: 'blue',
        title: 'Удаление сметы',
        content: 'Действительно удалить смету?<br/><hr/>',
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

// Открытие списка "Просмотрено"
$(document).popover({
    trigger: "click",
    sanitize: false,
    html: true,
    animation: true,
    selector: '.viewed',
    container: '#viewed_popover',
    template: '<div class="popover viewed_popover" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
});

// Скрытие информации о том, кто загрузил при развороте на весь экран
$('button.btn-tool[data-card-widget="maximize"]').click(function(){
    $(this).closest('div.card').find('div.card-footer').toggle();
});

// Растяжение по высоте поля комментария при увеличении текста
$('#estimate_comment').on('input',function(){
    resize_estimate_comment();
});
$().ready(function(){
    resize_estimate_comment();
});

function resize_estimate_comment(){
    let estimate_comment = $('#estimate_comment');
    if (estimate_comment.height() < estimate_comment.prop('scrollHeight')) {
        estimate_comment.height(5);
        estimate_comment.height(estimate_comment.prop('scrollHeight'));
    }
    if (estimate_comment.height() < 100) estimate_comment.height(100);
}

// Поделиться сметой
$('.share').click(function(){
    let title = $(this).data('doc_title'),
        url = $(this).data('doc_url');

    getFile(title,url).then((file) => shareFile(file))
        .catch((error) => console.log(error));

});

async function getFile(title,url){
    const file = await fetch(url).then(r => r.blob()).then(blobFile => new File([blobFile], title+".pdf", { type: "application/pdf" }));
    return file;
}

function shareFile(file) {
    let filesArray = [file];
    if (navigator.canShare && navigator.canShare({ files: filesArray })) {
        navigator.share({
            files: filesArray
        })
            .then()
            .catch((error) => alert('Не удалось поделиться'));
    } else {
        alert('Ваше устройство не поддерживает эту функцию');
    }
}
