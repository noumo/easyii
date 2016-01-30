(function($){

    $.fn.elementListView = function(method) {

        var prototype = $.ElementListView.prototype;

        if (prototype[method]) {
            return prototype[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return prototype.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.yiiGridView');
            return false;
        }
    };

    var defaults = {
        deleteElementSelector: '.delete-element',
        templateUrl: '',
        items: []
    };

    var contentElementData = {};

    $.ElementListView = function() {

    };

    $.ElementListView.prototype.init = function (options) {
        return this.each(function () {
            var $e = $(this);
            var settings = $.extend({}, defaults, options || {});
            contentElementData[$e.attr('id')] = {settings: settings};

            $(document)
                .off('click.elementListView', settings.deleteElementSelector)
                .on('click.elementListView', settings.deleteElementSelector, function(){
                    if($e.find('tr').length > 1) {
                        $(this).closest('tr').remove();
                    }
                    return false;
                });

            $.ElementListView.prototype.applyEvents.apply($e);

            $('#addElement').on('click', function(){
                var $this = $(this);
                $.ElementListView.prototype.addTemplateItem.apply($e, [$this.data('template')]);
            });
        });
    };

    $.ElementListView.prototype.addItem = function(item) {
        var $e = $(this);

        $e.append(item);
    };

    $.ElementListView.prototype.addTemplateItem = function(template) {
        var $e = $(this),
            settings = contentElementData[$e.attr('id')].settings;

        $.ajax({
            method: 'POST',
            url: settings.templateUrl,
            data: {id: template},
            context: $e,
            success: function(data) {
                $.ElementListView.prototype.addItem.apply(this, [data]);
            }
        });
    };

    $.ElementListView.prototype.applyEvents = function() {
        var $e = $(this);

        $e.on('click', '.move-up', function(){
            var current = $(this).closest('tr');
            var previos = current.prev();
            if(previos.get(0)){
                previos.before(current);
            }
            return false;
        });

        $e.on('click', '.move-down', function(){
            var current = $(this).closest('tr');
            var next = current.next();
            if(next.get(0)){
                next.after(current);
            }
            return false;
        });

        $e.on('change', '.element-type', function(){
            var $this = $(this);
            var type = $this.val();
            var options = $this.closest('tr').find('.element-options');

            if(optionsIsNeeded(type)){
                options.show();
            }else{
                options.hide();
            }
        });
    };

    function optionsIsNeeded(type)
    {
        return type == 'select' || type == 'checkbox' || type == 'file';
    }
})(jQuery);