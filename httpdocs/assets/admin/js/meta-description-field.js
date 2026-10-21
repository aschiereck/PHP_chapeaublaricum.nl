$(function() {
    //Impose maxlengths
    $('.meta-description').each(function () {
        var field = $(this);
        //Create counter field
        var counterId = field.attr('id') + '-' + 'max-length-counter';
        field.parent().find('label').append(' <span class="maxlength-counter" id="' + counterId + '"></span>');

        //Subscribe for updates
        field.change(function () {
            updateMaxlengthCounters(field)
        });
        field.keypress(function () {
            updateMaxlengthCounters(field)
        });
        field.keyup(function () {
            updateMaxlengthCounters(field)
        });
        //Hack for backspace button in chrome
        field.keydown(function (e) {
            if (e.keyCode == 8)
                field.trigger('keypress');
        });
        //Trigger init
        updateMaxlengthCounters(field)
    });
});

function updateMaxlengthCounters(field)
{
    var counter = $('#' + field.attr('id') + '-' + 'max-length-counter');
    var maxLength = 150;
    var value = field.val();

    if(value.length > maxLength)
    {
        value = value.substr(0, maxLength);
        field.val(value);
    }

    counter.html(' (' + value.length + ' van '  + maxLength + ' tekens gebruikt)');
}