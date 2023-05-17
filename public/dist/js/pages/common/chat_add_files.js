// Загрузка файлов
$('#add_file').click(function(evt){
    evt.preventDefault();
    $('#add_message_files').click();
});
$('#add_message_files').change(function(){
    update_file_previews();
});
function update_file_previews() {
    let files = document.getElementById('add_message_files').files,
        files_preview_block = $('#files_preview_block');
    files_preview_block.text('');
    if (files.length > 0) {
        files_preview_block.slideDown(200);
        $.each( files, function( index, file ){
            if (file.type.match('image.*')) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    files_preview_block.append('<div class="file_preview_item_block"><button class="btn btn-sm btn-danger file_preview_item_remove_btn" onclick="delete_file_preview(' + index + ')"><i class="fa fa-times" aria-hidden="true"></i></button><img src="'+e.target.result+'" class="file_preview"/></div>');
                }
                reader.readAsDataURL(file);
            } else if (file.type.match('video/mp4')) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    files_preview_block.append('<div class="file_preview_item_block"><button class="btn btn-sm btn-danger file_preview_item_remove_btn" onclick="delete_file_preview(' + index + ')"><i class="fa fa-times" aria-hidden="true"></i></button><video src="'+e.target.result+'" class="file_preview" autoplay loop muted playsinline/></div>');
                }
                reader.readAsDataURL(file);
            } else {
                files_preview_block.prepend('<div class="mb-2"><button class="btn btn-sm btn-danger file_preview_item_remove_filename_btn mr-2" onclick="delete_file_preview(' + index + ')"><i class="fa fa-times" aria-hidden="true"></i></button>'+file.name+'</div>');
            }

        });
    } else {
        files_preview_block.slideUp(200);
    }
}
function delete_file_preview(index) {
    let dt = new DataTransfer(),
        input = document.getElementById('add_message_files'),
        files = input.files
    for (let i = 0; i < files.length; i++) {
        if (index !== i) dt.items.add(files[i])
    }
    input.files = dt.files;
    update_file_previews();
}
