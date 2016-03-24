$(function(){
    $('.easyiicms-edit').each(function(i, element){
        var $this = $(element);
        $this.append('<a href="'+$this.data('edit')+'" class="easyiicms-goedit" style="width: '+$this.width()+'px; height: '+$this.height()+'px;" target="_blank"></a>');
    });
    $($('#easyii-navbar input').switcher({copy: {en: {yes: '', no: ''}}}).parent('.switcher')).on('click', function(){
        var checkbox = $(this).children('input');
        checkbox.switcher('setDisabled', true);
        location.href = checkbox.attr('data-link') + '/' + (checkbox.is(':checked') ? 1 : 0);
    });
    $('#easyii-navbar .live-edit-label').on('click', function(){
        $('#easyii-navbar .switcher').trigger('click');
    });
});