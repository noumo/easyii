(function($){

    $.fn.editableList = function(method) {

        var prototype = $.EditableList.prototype;

        if (prototype[method]) {
            return prototype[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return prototype.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.editableList');
            return false;
        }
    };

    var defaults = {
        modalSelector: '',
        templateUrl: '',
        deleteUrl: ''
    };

    var contentElementEvents = {
    };

    var editableListData = {};

    $.EditableList = function() {

    };

    $.EditableList.prototype.init = function (options) {
        return this.each(function () {
            var $e = $(this);
            var settings = $.extend({}, defaults, options || {});
            editableListData[$e.attr('id')] = {settings: settings};

            $.EditableList.prototype.applyEvents.apply($e);
        });
    };

    $.EditableList.prototype.addTemplateItem = function(modal) {
        var list = $(this),
            $modal = $(modal),
            settings = editableListData[list.attr('id')].settings,
            parentId = $modal.data('parent-id'),
            listSource = $modal.data('list-source');

        $modal.find('.content').load(listSource, '', function() {

            $modal.find('button[data-content-element]').on('click', function(){
                var type = $(this).data('content-element');

                $.ajax({
                    method: 'GET',
                    url: settings.templateUrl,
                    data: {type: type, parentId: parentId},
                    success: function(data) {
                        list.append(data);
                        $.EditableList.prototype.applyEvents.apply(list, [data]);

                        $modal.modal('hide');
                        $('html, body').animate({ scrollTop: ($(list).find('li:last').offset().top)}, 'slow');
                    }
                });
            });
        });
        /*
        $.ajax({
            method: 'POST',
            url: settings.templateUrl,
            data: {type: type},
            context: $e,
            success: function(data) {
                var $data = $('<div />').html(data);

                //var script = $data.find('script[type="text/javascript"]').html();

                // $data.find('script[type="text/javascript"]').remove();

                $.EditableList.prototype.addItem.apply(this, [data]);
                // $.EditableList.prototype.applyEvents.apply($e);
            }
        });
        */
    };

    $.EditableList.prototype.applyEvents = function() {
        var list = $(this),
            settings = editableListData[list.attr('id')].settings;

        $(settings.modalSelector)
            .off('show.bs.modal')
            .on('show.bs.modal', function(){
                $.EditableList.prototype.addTemplateItem.apply(list, [this]);
            });

        list.find('.js-remove')
            .off('click')
            .on('click', function (evt) {
                var el = $(evt.target).closest('li'); // get dragged item
                if (el) {
                    var id = el.data('element-id');

                    var button = $(this).addClass('disabled');
                    var title = button.attr('title');

                    if(confirm(title ? title+'?' : 'Confirm the deletion')){
                        $.ajax({
                            type: 'DELETE',
                            url: settings.deleteUrl + '?' + jQuery.param({elementId: id}),
                            success: function(data) {
                                el.remove();
                                el.hide();
                            }
                        });
                    }
                    else {
                        button.removeClass('disabled');
                    }
                }

                return false;
            });

        list
            .off('click', '.move-up')
            .on('click', '.move-up', function(){
                var current = $(this).closest('li');
                var previos = current.prev();

                if(previos.get(0)){
                    previos.before(current);
                }

                return false;
            });

        list
            .off('click', '.move-down')
            .on('click', '.move-down', function(){
                var current = $(this).closest('li');
                var next = current.next();

                if(next.get(0)){
                    next.after(current);
                }

                return false;
            });
    };

})(jQuery);