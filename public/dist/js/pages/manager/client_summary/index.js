// DataTable таблицы
let clientSummary = $("#clientSummary").DataTable({
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
    $('#clientSummary_filter')
        .closest('.row')
        .find('.col-sm-12.col-md-6:first-child')
        .html(table_buttons_block.html());
    table_buttons_block.remove();

    // Скрытие меню при загрузке страницы
    $(".dataTables_scrollBody").floatingScroll("init");
    $('[data-widget="pushmenu"]').PushMenu('collapse');
    // Проверка обновления данных другими сотрудниками раз в 8 секунд
    setInterval(dynamicUpdateSummary, 8000);
});

// Растяжение и сжатие столбцов таблицы при открытии-скрытии меню
$(document).on('collapsed.lte.pushmenu shown.lte.pushmenu', function () {
    setTimeout(function () {
        clientSummary.columns.adjust();
        $(".dataTables_scrollBody").floatingScroll("update");
    }, 500);
});

// Клик редактируемого td: первый - выделение рамкой, второй - открытие поля редактирования
$('#clientSummary').on('click', 'td.editable', function () {
    if ($('#cell_editing').length === 0) {
        if (!$(this).hasClass('clicked')) {
            $(this).addClass('clicked');
        } else if (!$(this).hasClass('editing')) {
            let element = $(this),
                text = element.text();
            element.addClass('editing').append('<div id="cell_editing"><textarea data-updated="' + text + '"></textarea><button id="close_without_change" class="btn btn-default btn-sm" data-text-onopen="' + text + '"><i class="fa fa-times" aria-hidden="true"></i><button id="undo" class="btn btn-default btn-sm"><i class="fa fa-undo" aria-hidden="true"></i></button><button id="redo" class="btn btn-default btn-sm"><i class="fa fa-repeat" aria-hidden="true"></i></button><button id="save_cell" class="btn btn-primary btn-sm"><i class="fa fa-floppy-o" aria-hidden="true"></i></button></div>');
            let textarea = element.find('textarea');
            textarea.focus().val(text);
            if (textarea.height() < textarea.prop('scrollHeight')) {
                textarea.height(5);
                textarea.height(textarea.prop('scrollHeight'));
                $('#cell_editing').height(element.prop('scrollHeight') + 3);
            }
            if ($('#cell_editing').height() > element.height()) {
                element.height($('#cell_editing').height());
            }
        }
    }
}).on('input', 'textarea', function(){
    // Растяжение по высоте поля редактирования при увеличении текста
    if ($(this).height() < $(this).prop('scrollHeight')) {
        $(this).height(5);
        $(this).height($(this).prop('scrollHeight'));
        $('#cell_editing,td.editing').height($(this).prop('scrollHeight') + 38);
    }
}).on('click', '#undo', function () {
    // Кнопка Undo
    let td = $(this).closest('td'),
        text_initial = td.data('initial'),
        textarea = $('#cell_editing textarea'),
        text_updated = td.data('updated'),
        text_now = textarea.val();
    if (text_updated !== text_now) {
        textarea.val(text_updated).data('updated',text_now);
    } else if (text_initial !== text_now) {
        textarea.val(text_initial).data('updated',text_now);
    }
}).on('click', '#redo', function () {
    // Кнопка Redo
    let textarea = $('#cell_editing textarea'),
        text_updated = textarea.data('updated');
    textarea.val(text_updated);
}).on('click', '#close_without_change', function () {
    // Кнопка Закрыть без изменений
    let td = $(this).closest('td'),
        text = $(this).data('text-onopen');
    td.removeClass('editing').removeClass('clicked').css('height','').text(text);
}).on('click', '#save_cell', function () {
    // Кнопка Сохранить
    let td = $(this).closest('td'),
        textarea = $('#cell_editing textarea'),
        text = textarea.val();
    // Сохраняем значение
    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: '/manage/client_summary/update',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: {client: td.data('client'), name: td.data('name'), value: text},
        success:function(result){
            td.removeClass('clicked').removeClass('editing').css('height','');
            clientSummary.cell(td).data(text);
            if (parseInt(result.updated,10) === 1) {
                $(document).Toasts('create', {
                    class: 'bg-success',
                    icon: 'fa fa-check fa-lg',
                    title: 'Данные сохранены',
                    autohide: true
                });
                if (td.data('initial') !== text) td.data('updated',text);
            } else {
                $(document).Toasts('create', {
                    class: 'bg-info',
                    icon: 'fa fa-check fa-lg',
                    title: 'Изменений не было',
                    autohide: true
                });
            }
        },
        error: function(){
            $(document).Toasts('create', {
                class: 'bg-danger',
                icon: 'fa fa-exclamation-triangle fa-lg',
                title: 'Ошибка',
                body: 'Данные не были сохранены'
            });
        }
    });
}).on('click', '.client-table-address', function () {
    // Подтверждение перехода в карточку объекта
    let link = $(this).data('link');
    $.alert({
        type: 'blue',
        title: 'Переход к объекту',
        content: 'Перейти в карточку объекта?<br/><hr/>',
        closeIcon: true,
        backgroundDismiss: true,
        buttons: {
            yes: {
                text: 'Перейти',
                btnClass: 'btn-primary',
                action: function(){
                    location.href = link;
                }
            },
            no: {
                text: 'Нет',
                btnClass: 'btn-default'
            }
        }
    });
});

// При клике вне поля выделенного td выделение снимается
$('body').on('click', function (e) {
    let clicked = $('#clientSummary td.editable.clicked');
    if ($('#cell_editing').length === 0 && clicked.length > 0) {
        clicked.each(function () {
            if (!$(this).hasClass('editing') && !$(this).is(e.target) && $(this).has(e.target).length === 0) {
                $(this).removeClass('clicked');
            }
        });
    }
});

// Проверка изменения данных другими пользователями
function dynamicUpdateSummary() {
    let updateInfo = $('#updateInfo');
    $.get("/manage/client_summary/check_updated",
        {
            updated_at: updateInfo.data('updated_at'),
        },
        function(response) {
            if (!$.isEmptyObject(response.clients)) {
                $.each(response.clients, function(client_id, values) {
                    $.each(values, function(name, value) {
                        if (name === 'in_process') {
                            let tr = $('#clientSummary tbody tr[data-client='+client_id+']');
                            if (parseInt(value,10) === 1) {
                                tr.addClass('in_process');
                            } else {
                                tr.removeClass('in_process');
                            }
                        } else {
                            let td = $('#clientSummary tbody tr td.editable[data-client='+client_id+'][data-name='+name+']');
                            if (!td.hasClass('editing')) {
                                clientSummary.cell(td).data(value);
                            }
                        }
                    });
                });
                updateInfo.data('updated_at',response.date);
            }
        }
    );
}
