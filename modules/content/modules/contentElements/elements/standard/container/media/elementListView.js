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
        modalSelector: '',
        deleteElementSelector: '.delete-element',
        addElementSelector: '[data-content-element]',
        templateUrl: '',
        parentId: ''
    };

    var contentElementEvents = {
        itemAdded: 'itemAdded'
    };

    var contentElementData = {};

    $.ElementListView = function() {

    };

    $.ElementListView.prototype.init = function (options) {
        return this.each(function () {
            var $e = $(this);
            var settings = $.extend({}, defaults, options || {});
            contentElementData[$e.attr('id')] = {settings: settings};

            $.ElementListView.prototype.applyEvents.apply($e);

            $(settings.modalSelector)
                .off('show.bs.modal')
                .on('show.bs.modal', function(){

                    $(this).find('.content').load($(this).data('modal-source'), '', function() {
                        $.ElementListView.prototype.applyEvents.apply($e);

                        $(settings.addElementSelector).on('click', function(){
                            var $this = $(this);
                            $.ElementListView.prototype.addTemplateItem.apply($e, [$this.data('content-element')]);
                        });
                    });
                });

        });
    };

    $.ElementListView.prototype.addItem = function(item) {
        var $e = $(this);

        $e.find('tbody').append(item);

        $e.trigger(contentElementEvents.itemAdded);
    };

    $.ElementListView.prototype.addTemplateItem = function(type) {
        var $e = $(this),
            settings = contentElementData[$e.attr('id')].settings;

        $.ajax({
            method: 'GET',
            url: settings.templateUrl,
            data: {type: type, parentId: settings.parentId},
            context: $e,
            success: function(data) {
                $.ElementListView.prototype.addItem.apply(this, [data]);
            }
        });
    };

    $.ElementListView.prototype.applyEvents = function() {
        var $e = $(this),
            settings = contentElementData[$e.attr('id')].settings;

        $(document)
            .off('click.elementListView', settings.deleteElementSelector)
            .on('click.elementListView', settings.deleteElementSelector, function(){
                if($e.find('tr').length > 1) {
                    var tr = $(this).closest('tr'),
                        id = tr.data('element-id'),
                        type = tr.data('element-type');

                    var name = 'Element[' + type + ':' + id + ']';
                    var scenarioInput = $('<input type="hidden" name="" value="">').attr('name', name + '[scenario]').val('delete');
                    var idInput = $('<input type="hidden" name="" value="">').attr('name', name + '[element_id]').val(id);
                    var typeInput = $('<input type="hidden" name="" value="">').attr('name', name + '[type]').val(type);

                    scenarioInput.insertAfter($e);
                    idInput.insertAfter($e);
                    typeInput.insertAfter($e);

                    tr.remove();
                }
                return false;
            });

        $e
            .off(contentElementEvents.itemAdded)
            .on(contentElementEvents.itemAdded, function(){
                $(settings.modalSelector).modal('hide');
            });

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
    };

})(jQuery);