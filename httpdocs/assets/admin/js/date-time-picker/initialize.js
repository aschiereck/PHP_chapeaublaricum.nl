$(function(){
    $('.datepicker').pickadate({
        today: 'Vandaag',
        clear: 'Verwijder',
        close: 'Sluiten',
        formatSubmit: 'yyyy-mm-dd'
    });
    $('.timepicker').pickatime({
        format: 'HH:i uur',
        formatSubmit: 'HH:i'
    });
    $('.minute-timepicker').pickatime({
        format: 'HH:i',
        formatSubmit: 'HH:i',
        interval: 5
    });
});
