$(function(){
    $('.easyiicms-edit').each(function(i, element){
        var $this = $(element);
        $this.append('<a href="'+$this.data('edit')+'" class="easyiicms-goedit" style="width: '+$this.width()+'px; height: '+$this.height()+'px;" target="_blank"></a>');
    });

    $('body').on('change', '#easyii-navbar input', function () {
        location.href = $(this).attr('data-link') + '/' + ($(this).is(':checked') ? 1 : 0);
    });
});