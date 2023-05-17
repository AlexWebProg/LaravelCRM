$("#clientsTable").DataTable({
    searching: true,
    responsive: {
        details: {
            type: 'column',
            target: 6
        }
    },
    lengthChange: false,
    autoWidth: false,
    fixedHeader: false,
    stateSave: false,
    paging: false,
    info: true,
    orderFixed: {
        "pre": [ 7, 'asc' ]
    },
    order: [[ 5, "desc" ]],
    columnDefs: [
        { responsivePriority: 1, targets: 0 },
        { orderable: false, targets: 1 },
        { orderable: false, targets: 2 },
        { orderable: false, targets: 3 },
        { orderable: false, targets: 4 },
        { orderable: false, targets: 6, defaultContent: "" },
        { target: 7, visible: false, searchable: false},
    ],
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
    },
});

// Новые события для всех объектов в списке
function checkNewEvents() {
    if (!document.hidden) {
        let client_ids = [];
        $('.client-table-badge').each(function () {
            client_ids.push($(this).data('client_id'));
        });
        $.get("/api/manager/checkNewEventsList",
            {
                manager_id: $('#logOutForm').data('id'),
                client_ids: client_ids
            },
            function( data ) {
                $('.client-table-badge').each(function () {
                    let client_id = $(this).data('client_id');
                    if (data[client_id]) {
                        $(this).text(data[client_id]).removeClass('d-none');
                    } else {
                        $(this).text('0').addClass('d-none');
                    }
                });
            });
    }
}
$().ready(function() {
    $('[data-widget="pushmenu"]').PushMenu('collapse');
    checkNewEvents();
    setInterval(checkNewEvents, 10000);
});
