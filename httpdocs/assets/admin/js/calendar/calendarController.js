
var calendarController =  function( $scope, calendarService) {

    var picker = $('#fc-date').pickadate('picker');
    var lastId = 0;

    $scope.events = calendarService.getEvents();
    $scope.selectedEvent = null;

    $scope.remove = function(id)
    {
        calendarService.remove(id);
    };

    $scope.createNewEvent = function()
    {
        $scope.selectedEvent = {
            id : lastId + 1,
            loction : '',
            name : '',
            date : null,
            time: null
        };
        showModal();
    }

    $scope.edit = function(id)
    {
        $scope.selectedEvent = jQuery.extend({}, calendarService.getById(id));
        showModal();
    }

    showModal = function(){
        picker.start();
        picker.set('select', moment($scope.selectedEvent.date).toDate());
        $('#edit-event-modal').modal('show');
    }

    $scope.saveEvent = function()
    {
        if($scope.selectedEvent.id)
        {
            var event = calendarService.getById($scope.selectedEvent.id);
            if(event)
            {
                calendarService.remove($scope.selectedEvent.id);
            }
        }

        var date = $('[name="date_submit"]').val();
        $scope.add(
            $scope.selectedEvent.id,
            date,
            $scope.selectedEvent.time,
            $scope.selectedEvent.name,
            $scope.selectedEvent.location
        );

        picker.stop();

        $('#edit-event-modal').modal('hide');
    }

    $scope.add = function(id, date, time, name, location)
    {
        if(id > lastId)
        {
            lastId = id;
        }
        calendarService.add(id, date, time, name, location);
    };
};
