$(function(){
    var lists = document.getElementsByClassName('sortable-list');
    for(var i =0; i < lists.length; i++)
    {
        var list = lists[i];

        //Create closure for list
        var sortableListHandler = function(list) {
            Sortable.create(list, {
                animation: 500,
                handle: ".icon-move",
                chosenClass: "sortable-chosen",
                onEnd: function (evt) {
                    var l = $(list);
                    var url = l.attr('data-sortable-target');
                    var ids = [];

                    if (!url) {
                        throw "No target defined for sortable list";
                    }

                    l.find('[data-id]').each(function () {
                        ids.push($(this).attr('data-id'));
                    });
                    $.post(
                        l.attr('data-sortable-target'),
                        {"position": ids.join(',')}
                    );
                }
            });
        };
        sortableListHandler(list);
    }
});