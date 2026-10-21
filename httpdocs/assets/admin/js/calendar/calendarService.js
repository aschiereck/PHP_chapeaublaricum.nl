/**
 * @returns {{addPhoto: Function, remove: Function}}
 */
var calendarService = function() {

    var events = [];

    /**
     * @param id
     * @param url
     * @param name
     * @param position
     */
    var add = function(id, date,time, name, location)
    {
        events.push({
            "id" : id,
            "date" : date,
            "formattedDate" : moment(date).format('dddd D MMMM YYYY'),
            "time" : time,
            "name" : name,
            "location" : location
        })
    };

    /**
     * @param id
     */
    var remove = function(id)
    {
        for(var i = 0; i < events.length; i++)
        {
            var event = events[i];
            if(event.id == id)
            {
                events.splice(i,1);
                return;
            }
        }
    };

    /**
     * @param id
     */
    var getById = function(id)
    {
        for(var i = 0; i < events.length; i++)
        {
            var event = events[i];
            if(event.id == id)
            {
               return event;
            }
        }
    }

    var getEvents = function()
    {
        return events;
    };

    return {
        add: add,
        remove: remove,
        getEvents : getEvents,
        getById : getById
    };
};