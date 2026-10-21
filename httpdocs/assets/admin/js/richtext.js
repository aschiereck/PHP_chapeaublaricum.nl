var globalUploaderCallBack;

$(function(){
    tinymce.init({
        selector: 'textarea.rich-text',
        height: 400,
        menu: {},
        convert_urls: false,
        plugins: [
            'advlist autolink lists link image charmap anchor',
            'searchreplace visualblocks code fullscreen',
            'media table contextmenu paste code'
        ],
        valid_elements : '*[*]',
        automatic_uploads : true,
        images_upload_base_path : '/',
        images_upload_credentials: true,
        file_picker_types: 'file image',
        file_picker_callback: function(callback, value, meta) {


            // Provide file and text for the link dialog
            if (meta.filetype == 'file') {
                globalUploaderCallBack = callback;
                $('#attachmentUploadForm input').click();
            }

            // Provide image and alt text for the image dialog
            if (meta.filetype == 'image') {
                globalUploaderCallBack = callback;
                $('#imageUploadForm input').click();
            }
        },
        toolbar: 'styleselect | bold italic underline removeformat |  undo redo | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | code',
        content_css: '/assets/editor.css',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });
});

function uploadAttachment()
{
    var form = $('#attachmentUploadForm');
    uploadFiles(form);
}

function uploadImage()
{
    var form = $('#imageUploadForm');
    uploadFiles(form);
}

function uploadFiles(form)
{
    var files = form.find('input[type=file]').prop("files");
    var data = new FormData();
    $.each(files, function(key, value)
    {
        data.append(key, value);
    });

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: data,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function(data, textStatus, jqXHR)
        {
            if(typeof data.error === 'undefined')
            {
                if(data.success)
                {
                    globalUploaderCallBack(data.url, {alt: data.name, text: data.name});
                }
                else
                {
                    alert('Uploaden mislukt: ' + data.errorMessage);
                    globalUploaderCallBack('', {alt: '', text: ''});
                }
            }
            else
            {
                alert('Er is iets misgegaan met uploaden');
                console.log('ERRORS: ' + data.error);
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            alert('Er is iets misgegaan met uploaden');
            console.log('ERRORS: ' + textStatus);
        }
    });
}

/**
 * Workaround for problem that url field was not selectable when TinyMCE is loaded in Bootstrap modal
 * @see http://archive.tinymce.com/develop/bugtracker_view.php?id=6001
 * @see http://jsfiddle.net/e99xf/13/
 */
$(document).on('focusin', function(e) {
    if ($(event.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
    }
});