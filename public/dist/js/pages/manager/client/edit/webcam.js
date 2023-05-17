// Веб-камера -----------------------------------------------------
// Открытие ссылки на вебкамеру
$('#webCamView').click(function(){
    let href = $('#webCamHref').val();
    window.open(href);
});
