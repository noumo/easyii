$(function(){
    var body = $('body');
    body.on('click', '.confirm-delete', function(){
        var button = $(this).addClass('disabled');
        var title = button.attr('title');

        if(confirm(title ? title+'?' : 'Confirm the deletion')){
            if(button.data('reload')){
                return true;
            }
            $.getJSON(button.attr('href'), function(response){
                button.removeClass('disabled');
                if(response.result === 'success'){
                    notify.success(response.message);
                    button.closest('tr').fadeOut(function(){
                        this.remove();
                    });
                } else {
                    alert(response.error);
                }
            });
        }
        return false;
    });

    body.on('click', '.move-up, .move-down', function(){
        var button = $(this).addClass('disabled');

        $.getJSON(button.attr('href'), function(response){
            button.removeClass('disabled');
            if(response.result === 'success' && response.swap_id){
                var current = button.closest('tr');
                var swap = $('tr[data-id=' + response.swap_id + ']', current.parent());

                if (swap.get(0)) {
                    if (button.hasClass('move-up')) {
                        swap.before(current);
                    } else {
                        swap.after(current);
                    }
                } else {
                    location.reload();
                }
            }
            else if(response.error){
                alert(response.error);
            }
        });

        return false;
    });

    $('.switch').switcher({copy: {en: {yes: '', no: ''}}}).on('change', function(){
        var checkbox = $(this);
        checkbox.switcher('setDisabled', true);

        $.getJSON(checkbox.data('link') + '/' + (checkbox.is(':checked') ? 'on' : 'off') + '/' + checkbox.data('id'), function(response){
            if(response.result === 'error'){
                alert(response.error);
            }
            if(checkbox.data('reload')){
                location.reload();
            }else{
                checkbox.switcher('setDisabled', false);
            }
        });
    });

    $(document).bind('keydown', function (e) {
        if(e.ctrlKey && e.which === 83){ // Check for the Ctrl key being pressed, and if the key = [S] (83)
            $('.model-form').submit();
            e.preventDefault();
            return false;
        }
    });

    window.notify = new Notify();
    $('.fancybox').fancybox();
});

var Notify = function() {
    var div = $('<div id="notify-alert"></div>').appendTo('body');
    var queue = [];
    var _this = this;

    this.success = function(text)
    {
        queue.push({type : 'success', text: text, icon: 'ok-sign'});
        _this.proceedQueue();
    }
    this.error = function(text)
    {
        queue.push({type : 'danger', text: text, icon: 'info-sign'});
        _this.proceedQueue();
    }

    this.proceedQueue = function()
    {
        if(queue.length > 0 && !div.is(":visible"))
        {
            div.removeClass().addClass('alert alert-' + queue[0].type).html('<span class="glyphicon glyphicon-' + queue[0].icon + '"></span> ' + queue[0].text);
            div.fadeToggle();

            setTimeout(function(){
                queue.splice(0,1);
                div.fadeToggle(function(){ _this.proceedQueue();});
            }, 3000);
        }
    }
};
