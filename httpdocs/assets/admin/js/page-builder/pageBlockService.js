
var pageBlockService = function(moduleService) {

    var pageBlocks = [];

    var add = function(pageBlock, autoPlacement)
    {
        autoPlacement = typeof autoPlacement !== 'undefined' ? autoPlacement : true;

        var module = moduleService.getByTemplate(pageBlock.template);
        if(!module)
        {
            console.log('ERROR: Could not find module for template: ' + pageBlock.template);
        }

        pageBlock.pageBlockId = createGuid();
        pageBlocks.push(pageBlock);

        var editIcon = module.canEdit ? '<span class="icon-settings"></span>' : '';
        var deleteIcon = '<span class="icon-rubbish"></span>';

        var icon = '<div class="icon-block">'+editIcon+deleteIcon+'</div>';
        var titleHtml = module.title + '<span class="sub-name">' + (pageBlock.editorTitle ? ' - ' + pageBlock.editorTitle : '') + '</span>'
        var content = '<div><div class="grid-stack-item-content panel-heading module-'+module.tag+'" data-pageblock-id="'+pageBlock.pageBlockId+'">' + titleHtml + icon + '</div></div>';

        var x = autoPlacement ? null : pageBlock.x;
        var y = autoPlacement ? null : pageBlock.y;
        var width = autoPlacement ? module.defaultWidth : pageBlock.width;

        var grid = getGrid();
        grid.addWidget(
            $(content),
            x,
            y,
            width,
            1,
            autoPlacement,
            module.minWidth,
            12,
            1,
            1
        );

        return pageBlock;
    };

    var remove = function(pageBlockId)
    {
        for(var i = 0; i < pageBlocks.length; i++)
        {
            var pageBlock = pageBlocks[i];
            if(pageBlock.pageBlockId == pageBlockId)
            {
                var grid = getGrid();
                var widget =  getWidget(pageBlockId);
                grid.removeWidget(widget);

                pageBlocks.splice(i,1);
                return;
            }
        }
    };

    var update = function(pageBlockId, newData)
    {
        for(var i = 0; i < pageBlocks.length; i++)
        {
            var pageBlock = pageBlocks[i];
            if(pageBlock.pageBlockId == pageBlockId)
            {
                newData.id = pageBlock.id;
                newData.pageBlockId = pageBlock.pageBlockId;
                newData.x = pageBlock.x;
                newData.y = pageBlock.y;
                newData.width = pageBlock.width;
                pageBlocks[i] = newData;

                var widget = getWidget(pageBlock.pageBlockId);
                widget.find('.sub-name').html(newData.editorTitle ? ' - ' + newData.editorTitle : '');

                return;
            }
        }
    };


    var getWidget = function(pageBlockId)
    {
        return $('[data-pageblock-id="'+pageBlockId+'"]').parent();
    };


    var getGrid = function()
    {
        return $('#pagebuilder-stack').data('gridstack');
    };

    /**
     * @param pageBlockId
     */
    var getById = function(pageBlockId)
    {
        for(var i = 0; i < pageBlocks.length; i++)
        {
            var pageBlock = pageBlocks[i];
            if(pageBlock.pageBlockId == pageBlockId)
            {
                return pageBlock;
            }
        }
    };

    var updateGridStackAttributes = function()
    {
        for(var i = 0; i < pageBlocks.length; i++)
        {
            var pageBlock = pageBlocks[i];
            var widget = getWidget(pageBlock.pageBlockId);

            pageBlock.x = widget.attr('data-gs-x');
            pageBlock.y = widget.attr('data-gs-y');
            pageBlock.width = widget.attr('data-gs-width');
        }
    };

    var createGuid = function() {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
        }
        return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
            s4() + '-' + s4() + s4() + s4();
    };

    var getAll = function()
    {
        return pageBlocks;
    };

    return {
        add: add,
        remove: remove,
        update: update,
        getById: getById,
        updateGridStackAttributes : updateGridStackAttributes,
        getAll : getAll
    };
};