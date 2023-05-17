// Переход к началу страницы
$('.moveToTop').click(function(){
    $("html, body").animate({ scrollTop: 0 }, 500);
});

// Отключаем авто инициализацию Dropzone
Dropzone.autoDiscover = false;

// Текстовый редактор summenrnote
$(document).ready(function () {
    $('.summernote').summernote({
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ]
    });
});

// Загрузка файла
$(function () {
    bsCustomFileInput.init();
});

// Select2
$('.select2').select2();

// Автоматическое скрытие оповещений об успехе
$(document).ready(function () {
    setTimeout(function(){
        $('.toast.bg-success').toast('hide')
    },2000);
});

// Действия кнопок сохранения формы
$('#form_actions button.btn.btn-danger').click(function(){
    location.reload();
});
$('#form_actions button.btn.btn-success').click(function(){
    $('#main_form_submit').click();
});

// Отображение кнопок сохранения формы
function showFormActions(){
    $('#form_actions').css('bottom','0');
}

// Отображение кнопок сохранения формы при изменении поля ввода
$('#main_form input, #main_form select, #main_form textarea').bind('change, input', function(){
    showFormActions();
});
// Отключаем срабатывание на datetimepicker
$('#main_form input.datetimepicker-input')
    .unbind('input')
    .bind('change change.datetimepicker', function(){
        if ($(this).val() !== this.defaultValue) {
            showFormActions();
        }
    });

// Убираем класс ошибки валидации при изменении поля ввода
$('.is-invalid').bind('keyup change input change.datetimepicker', function(){
    $(this).removeClass('is-invalid');
    $(this).closest('.form-group').find('div.text-danger').remove();
});

// JQuery Sortable: перетягиваемые вверх-вниз строки для изменения сортировки
$(function() {
    $(".sortable_rows").sortable({
        axis: "y",
        containment: ".sortable_rows",
        cursor: "ns-resize",
        handle: ".sortable_handle",
        items: "> div.row",
        scroll: true,
        tolerance: "pointer",
        classes: {
            "ui-sortable-helper": "sorting_now"
        },
        change: function( event, ui ) {showFormActions();}
    }).disableSelection();
});

// Если есть сообщения об ошибках формы, показываем её кнопки сохранения
$().ready(function(){
    if ($('#validation_errors_notification').length && $('#main_form').length) {
        showFormActions();
    }
});

// serviceWorker for PWA
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register("/serviceWorker.js", {
        scope: '/manage'
    });
}

// Закрытие Popover при клике за его пределами
$('body').on('click', function (e) {
    $('[data-toggle=popover]').each(function () {
        // hide any open popovers when the anywhere else in the body is clicked
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
});

// Сворачивание-разворачивание списков пользователей в popover "Просмотрено"
$(document).on('click', '.viewed_popover_header_viewed',function () {
    $('.viewed_popover_item_viewed').slideToggle(200);
    $(this).find('i.fa-chevron-up').toggleClass('opened');
    updatePopoverUsingScroll();
}).on('click', '.viewed_popover_header_unviewed',function () {
    $(this).toggleClass('viewed_popover_header_unviewed_collapsed');
    $('.viewed_popover_item_unviewed').slideToggle(200);
    $(this).find('i.fa-chevron-up').toggleClass('opened');
    updatePopoverUsingScroll();
});
function updatePopoverUsingScroll(){
    setTimeout(function(){
        $(window).scrollTop($(window).scrollTop()+1);
        $(window).scrollTop($(window).scrollTop()-1);
    },201);
}

// Переключает между десктопной и мобильной версией сайта для возможности масштабирования
$('.viewport_zoom_btn').click(function(){
    let meta_viewport = $('meta[name=viewport]'),
        meta_content = meta_viewport.attr('content'),
        btn_content = $(this).data('content');
    meta_viewport.attr('content',btn_content);
    $(this).data('content',meta_content);
    $(this).find('i').toggleClass('fa-search-minus').toggleClass('fa-search-plus');
    setTimeout(function () {
        $('[data-widget="pushmenu"]').PushMenu('collapse');
    }, 100);
});

// Поле ввода суммы с формулой
function formatInputSum(input) {
    try {
        let val = input.val().replace(',', '.').replace(/[^0-9-+*/.]/gi, ''),
            sum_for_input = parseFloat(math.evaluate(val)).toFixed(2),
            sum_for_addon = new Intl.NumberFormat("ru", {style: "decimal", minimumFractionDigits: 2, maximumFractionDigits: 2}).format(parseFloat(sum_for_input)),
            addon = input.closest('.input-group').find('.input-group-text'),
            hidden = input.closest('.input-group').find('input[type="hidden"]');
        input.val(val);
        if (val.length) {
            addon.html('=<span class="mx-2">' + sum_for_addon + '</span><i class="fa fa-rub" aria-hidden="true"></i>');
            hidden.val(sum_for_input);
        } else {
            addon.html('<i class="fa fa-rub" aria-hidden="true"></i>');
            hidden.val('');
        }
    }
    catch(e) {}
}
