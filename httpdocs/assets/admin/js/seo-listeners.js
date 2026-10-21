$(function(){


    /** Element containing focuswords, only one element is supported per page **/
    var focusElement = null;

    /** Listener elements **/
    var listeners = [];

    /**
     * @returns array
     */
    var getFocusWords = function(){
        return focusElement.val().split(',');
    };

    /**
     * Count occurence of focuswords in a string
     * @param stringToSearch
     * @returns {number}
     */
    var countFocusWords = function(stringToSearch)
    {
        var focusWords = getFocusWords();
        var hits = 0;
        stringToSearch.replace(/<(?:.|\n)*?>/gm, '');

        for (var i = 0; i < focusWords.length; i++) {
            var word = focusWords[i].trim();
            if(word != '') {
                var regex = new RegExp(word, 'gi');
                var foundWords = stringToSearch.match(regex);
                if (foundWords) {
                    hits = hits + foundWords.length;
                }
            }
        }
        return hits;
    };

    /** Main update function **/
    var updateListenerStatus = function(listenerObj){
        var parent = listenerObj.parent();
        var value = listenerObj.val().trim();
        var numberrOfFocusWords = countFocusWords(value);
        var indicator = parent.find('.seo-indicator');

        parent.removeClass('seo-status-bad');
        parent.removeClass('seo-status-ok');
        parent.removeClass('seo-status-good');

        indicator.removeClass('icon-sad');
        indicator.removeClass('icon-neutral');
        indicator.removeClass('icon-smile');

        if(value == '')
        {
            parent.addClass('seo-status-bad');
            indicator.addClass('icon-sad');
        }
        else if(numberrOfFocusWords == 0)
        {
            parent.addClass('seo-status-ok');
            indicator.addClass('icon-neutral');
        }
        else
        {
            parent.addClass('seo-status-good');
            indicator.addClass('icon-smile');
        }
    };

    /** Set triggers **/
    var init = function(){

        var allFocusElements = $('[data-seo-listeners]');

        if(allFocusElements.length == 0)
        {
            return;
        }

        focusElement = $(allFocusElements[0]);

        if(!focusElement.attr('data-seo-listeners'))
        {
            return;
        }

        var listenersNames = focusElement.attr('data-seo-listeners').split(',');

        for(var i = 0; i < listenersNames.length; i++)
        {
            var listenerId = 'fc-' + listenersNames[i];
            var listener = $('#' + listenerId);
            var label = $(listener.parent()).find('label');

            label.after('<span class="seo-indicator"></span>');

            listener.addClass('seo-form-control');
            listener.on('change keyup', function(){
                updateListenerStatus($(this));
            });

            listeners.push(listener);
        }

        focusElement.on('change', function(){
            for(var i = 0; i < listeners.length; i++)
            {
                updateListenerStatus(listeners[i]);
            }
        }).change();
    };

    init();

});

