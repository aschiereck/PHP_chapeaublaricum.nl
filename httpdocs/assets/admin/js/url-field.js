$(function(){
    $('[data-url-listener]').each(function(){
        var elem = $(this);
        var listenTo = $('#' + elem.attr('data-url-listener'));

        listenTo.blur(function(){
            if(elem.val() == '')
            {
                var url = stringToUrl(listenTo.val());
                elem.val(url);
            }
        })
    });
});

function stringToUrl(str)
{
    str = str.replace(/ /g, '-');
    str = str.replace(/[^a-zA-Z0-9-_]/g, '').toLowerCase();
    return str;
}
