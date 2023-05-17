$("#managersTable").DataTable({
    searching: true,
    responsive: true,
    lengthChange: false,
    autoWidth: false,
    fixedHeader: false,
    stateSave: true,
    paging: false,
    info: true,
    order: [[ 0, "asc" ]],
    columnDefs: [
        { orderable: false, targets: -1 },
        { responsivePriority: 1, targets: 0 },
        { responsivePriority: 2, targets: -1 }
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
