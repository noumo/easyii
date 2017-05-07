$(function(){
    var table = $('#categoryFields > tbody');

    table.on('click', '.delete-field', function(){
        if(table.find('tr').length > 1) {
            $(this).closest('tr').remove();
        }
        return false;
    });

    table.on('click', '.move-up', function(){
        var current = $(this).closest('tr');
        var previos = current.prev();
        if(previos.get(0)){
            previos.before(current);
        }
        return false;
    });

    table.on('click', '.move-down', function(){
        var current = $(this).closest('tr');
        var next = current.next();
        if(next.get(0)){
            next.after(current);
        }
        return false;
    });

    table.on('change', '.field-type', function(){
        var $this = $(this);
        var type = $this.val();
        var options = $this.closest('tr').find('.field-options');

        if(optionsIsNeeded(type)){
            options.show();
        }else{
            options.hide();
        }
    });

    $('#addField').on('click', function(){
        table.append(fieldTemplate);
    });

    $('#saveCategoryBtn').on('click', function(){
        var form = '<input type="hidden" name="save" value="1">';
        table.find('tr').each(function(i, element) {
            var $this = $(element);
            var data = {
                name : $.trim($this.find('.field-name').val()),
                title : $.trim($this.find('.field-title').val()),
                type : $this.find('.field-type').val(),
                options : $this.find('.field-options').val()
            };
            if(data.name != '') {
                form += '<input type="hidden" name="Field[' + i + ']" value=\'' + JSON.stringify(data) + '\'>';
            }
        });
        $('<form method="post">' + form + '</form>').appendTo('body').submit();

        return false;
    });

    function optionsIsNeeded(type)
    {
        return fieldsWithOptions.indexOf(type) !== -1;
    }
});