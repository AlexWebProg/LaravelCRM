// DataTable таблицы
let matReport = $("#matReport").DataTable({
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
    fixedColumns: {
        left: 1
    },
    // bAutoWidth: false,
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
    $('#matReport_filter')
        .closest('.row')
        .find('.col-sm-12.col-md-6:first-child')
        .html(table_buttons_block.html())
        .addClass('d-none')
        .addClass('d-md-block');
    table_buttons_block.remove();

    // Скрытие меню при загрузке страницы
    $(".dataTables_scrollBody").floatingScroll("init");
    $('[data-widget="pushmenu"]').PushMenu('collapse');
});

// Растяжение и сжатие столбцов таблицы при открытии-скрытии меню
$(document).on('collapsed.lte.pushmenu shown.lte.pushmenu', function () {
    setTimeout(function () {
        matReport.columns.adjust();
        $(".dataTables_scrollBody").floatingScroll("update");
    }, 500);
});

$().ready(function () {
    // Месяц и год отчёта
    $('#month_year').datetimepicker({
        locale: 'ru',
        format: 'MMMM YYYY'
    });
    // Год отчёта
    $('#input_year').datetimepicker({
        locale: 'ru',
        format: 'YYYY'
    });
});

// Смена года месяца при изменении значения поля ввода
$('#month_year')
    .unbind('input')
    .bind('change change.datetimepicker', function(){
        if ($(this).val() !== this.defaultValue) {
            $('#month_year_form').submit();
        }
    });

// Смена года при изменении значения поля ввода
$('#input_year')
    .unbind('input')
    .bind('change change.datetimepicker', function(){
        if ($(this).val() !== this.defaultValue) {
            $('#year_form').submit();
        }
    });
