$(function(){
    var photosBody = $('#photo-table > tbody');
    var uploadButton = $('#photo-upload');
    var uploadingText = $('#uploading-text');
    var uploadingTextInterval;

    if(location.hash){
        $('img'+location.hash).closest('tr').addClass('info');
    }

    uploadButton.on('click', function(){
        $('#photo-file').trigger('click');
    });
    $('#photo-file').on('change', function(){
        var $this = $(this);
        var fileData = $this.prop('files')[0];
        var formData = new FormData();
        formData.append('Photo[image]', fileData);
        uploadButton.addClass('disabled');
        uploadingText.show();
        uploadingTextInterval = setInterval(dotsAnimation, 300);

        $.ajax({
            url: '/admin/photos/upload?module='+$this.data('module')+'&item_id='+$this.data('id'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: 'post',
            success: function(response){
                uploadButton.removeClass('disabled');
                uploadingText.hide();
                clearInterval(uploadingTextInterval);

                if(response.result === 'success'){
                    var html = $(photoTemplate
                        .replace(/\{\{photo_id\}\}/g, response.photo.id)
                        .replace(/\{\{photo_thumb\}\}/g, response.photo.thumb)
                        .replace(/\{\{photo_image\}\}/g, response.photo.image)
                        .replace(/\{\{photo_description\}\}/g, ''))
                        .hide().prependTo(photosBody).fadeIn();

                    notify.success(response.message);
                    checkEmpty();
                    $('.colorbox').colorbox();
                }else{
                    alert(response.error);
                }
            }
        });
    });

    photosBody.on('input propertychange', '.photo-description', function(){
        var saveBtn = $(this).siblings('.save-photo-description');
        if(saveBtn.hasClass('disabled')){
            saveBtn.removeClass('disabled').on('click', function(){
                var $this = $(this).unbind('click').addClass('disabled');
                var tr = $this.closest('tr');
                var text = $this.siblings('.photo-description').val();
                $.post(
                    '/admin/photos/description/'+ tr.data('id'),
                    {description: text},
                    function(response){
                        if(response.result === 'success'){
                            notify.success(response.message);
                            tr.find('.colorbox').attr('title', text);
                        }
                        else{
                            alert(response.error);
                        }
                    },
                    'json'
                );
                return false;
            });
        }
    });

    photosBody.on('click', '.change-image-button', function(){
        $(this).parent().find('.change-image-input').trigger('click');
        return false;
    });

    photosBody.on('change', '.change-image-input', function(){
        var $this = $(this);
        var tr = $this.closest('tr');
        var fileData = $this.prop('files')[0];
        var formData = new FormData();
        var changeButton = $this.siblings('.change-image-button').addClass('disabled');
        formData.append('Photo[image]', fileData);
        $.ajax({
            url: '/admin/photos/image/'+tr.data('id'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: 'post',
            success: function(response){
                changeButton.removeClass('disabled');
                if(response.result === 'success'){
                    tr.find('.colorbox').attr('href', response.photo.image).children('img').attr('src', response.photo.thumb);
                    notify.success(response.message);
                }else{
                    alert(response.error);
                }
            }
        });
    });

    photosBody.on('click', '.delete-photo', function(){
        var $this = $(this).addClass('disabled');
        if(confirm($this.attr('title')+'?')){
            $.getJSON($this.attr('href'), function(response){
                $this.removeClass('disabled');
                if(response.result === 'success'){
                    notify.success(response.message);
                    $this.closest('tr').fadeOut(function(){
                        $(this).remove();
                        checkEmpty();
                    });
                } else {
                    alert(response.error);
                }
            });
        }
        return false;
    });

    function checkEmpty(){
        var table = photosBody.parent();
        if(photosBody.find('tr').length) {
            if(!table.is(':visible')) {
                table.show();
                $('.empty').hide();
            }
        }
        else{
            table.hide();
            $('.empty').show();
        }
    }

    var dots = 0;
    function dotsAnimation() {
        dots = ++dots % 4;
        $("span", uploadingText).html(Array(dots+1).join("."));
    }
});