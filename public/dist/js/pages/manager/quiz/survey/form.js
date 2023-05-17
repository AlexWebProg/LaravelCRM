function showTemplate(){
    let template_id = $('#template_id').val();
    $('.template:not(.d-none)').addClass('d-none');
    if (template_id > 0) {
        $('#t_'+template_id).removeClass('d-none');
    }
}

function clientCheck(){
    let main = $('#all_clients').prop('checked');
    $('.client_check').each(function(){
        $(this).prop('checked',main);
    });
}

$().ready(function (){
    showTemplate();
});

$('#template_id').change(function(){
    showTemplate();
});

$('#all_clients').change(function(){
    let state = $(this).prop('checked');
    $('.client_group_check,.client_check').each(function(){
        $(this).prop('checked',state);
    });
});

$('.client_group_check').change(function(){
    let state = $(this).prop('checked'),
        client_class = $(this).attr('name').replace('to_','');
    $('.'+client_class).each(function(){
        $(this).prop('checked',state);
    });
    if ($('.client_check:not(:checked)').length) {
        $('#all_clients').prop('checked',false);
    } else {
        $('#all_clients').prop('checked',true);
    }
});

$('.client_check').change(function(){
    if ($('.client_check:not(:checked)').length) {
        $('#all_clients').prop('checked',false);
    } else {
        $('#all_clients').prop('checked',true);
    }
    if ($('.client_check.ob_status_2:not(:checked)').length) {
        $('#to_ob_status_2').prop('checked',false);
    } else {
        $('#to_ob_status_2').prop('checked',true);
    }
    if ($('.client_check.in_process_1:not(:checked)').length) {
        $('#to_in_process_1').prop('checked',false);
    } else {
        $('#to_in_process_1').prop('checked',true);
    }
    if ($('.client_check.in_process_0:not(:checked)').length) {
        $('#to_in_process_0').prop('checked',false);
    } else {
        $('#to_in_process_0').prop('checked',true);
    }
});
