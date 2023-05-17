$('.dismissible_link').click(function(){
    $(this).find('i.fa-refresh').addClass('fa-spin');
});

// Копирование ссылки на демо в буфер
$('#copy_demo_link_btn').click(function (evt){
    evt.preventDefault();
    navigator.clipboard.writeText($(this).data('url'));
    $(document).Toasts('create', {
        class: 'bg-success',
        icon: 'fa fa-check fa-lg',
        title: 'Ссылка скопирована',
        autohide: true
    });
});
