// DataTable таблицы
let expenses_object = $("#expenses_object").DataTable({
    stateSave: false,
    paging: false,
    info: true,
    searching: true,
    responsive: false,
    scrollX: true,
    scrollCollapse: true,
    fixedHeader: {
        headerOffset: 56
    },
    ordering: false,
    language: {
        "lengthMenu": "По _MENU_ записей",
        "search": "Поиск: ",
        "info": "Найдено: _TOTAL_ ",
        "infoEmpty": "К сожалению, ничего не найдено.",
        "emptyTable": "К сожалению, ничего не найдено",
        "zeroRecords": "Совпадений не найдено.",
        "sInfoFiltered": "(из _MAX_)",
        "paginate": {
            "previous": "Пред.",
            "next": "След.",
            "last": "Последний",
            "first": "Первый",
            "page": "Страница",
            "pageOf": "из"
        }
    }
});

$().ready(function () {
    // Кнопка экспорта в excel рядом с поиском
    let table_buttons_block = $('#table_buttons_block');
    $('#expenses_object_filter')
        .closest('.row')
        .find('.col-sm-12.col-md-6:first-child')
        .html(table_buttons_block.html());
    table_buttons_block.remove();

    // Скрытие меню при загрузке страницы
    $(".dataTables_scrollBody").floatingScroll("init");
    $('[data-widget="pushmenu"]').PushMenu('collapse');

    // Формат сумм, заложенных по смете
    formatInputSum($('#expense_estimate_sum_1'));
    formatInputSum($('#expense_estimate_sum_2'));
});

// Растяжение и сжатие столбцов таблицы при открытии-скрытии меню
$(document).on('collapsed.lte.pushmenu shown.lte.pushmenu', function () {
    setTimeout(function () {
        expenses_object.columns.adjust();
        $(".dataTables_scrollBody").floatingScroll("update");
    }, 500);
});

// Формат сумм, заложенных по смете
$('#expense_estimate_sum_1,#expense_estimate_sum_2').bind('input change',function(){
    formatInputSum($(this));
});

// Редактирование заложенных сумм
$('#modal_estimate_form_submit_button').click(function(){
    $('#modal_estimate_form_submit').click();
});
