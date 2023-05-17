$('.faq_question_link').click(function(){
    let icon = $(this).find('i.fa-chevron-down'),
        opening = false;
    if (!icon.hasClass('opened')) opening = true;
    $('.answer_toggle_icon > i.fa-chevron-down.opened').removeClass('opened');
    if (opening) {
        icon.addClass('opened');
    }
});
