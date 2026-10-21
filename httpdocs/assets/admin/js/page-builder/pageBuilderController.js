
var pageBuilderController =  function( $scope, pageBlockService, moduleService) {

    var urlPrefix = "";
    var editorCount = 1;
    $scope.modules = moduleService.getAll();

    $scope.json = '{}';

    $scope.submitOptionsForm = function(url)
    {
        var form = $('#edit-pageblock-options .modal-content .container form');
        loadOptionsForm(url, form.serialize());
    };

    $scope.addModule = function(json)
    {
        var module = JSON.parse(json);
        moduleService.add(module);
        $scope.$apply();
    };

    $scope.setUrlPrefix = function(prefix)
    {
        urlPrefix = prefix;
    }

    $scope.createPageBlock = function(module)
    {
        $('#overview-modules').modal('hide');
        loadOptionsForm(module.route + '?template=' + module.template);
    };

    $scope.editPageBlock = function(pageBlockId)
    {
        var pageBlock = pageBlockService.getById(pageBlockId);
        var module = moduleService.getByTemplate(pageBlock.template);
        if(!module)
        {
            console.log('ERROR: Could not find module for template: ' + pageBlock.template);
        }
        loadOptionsForm(module.route + '?template=' + module.template + '&blockId=' + pageBlock.pageBlockId , pageBlock);
    };

    $scope.remove = function(pageBlockId)
    {
        pageBlockService.remove(pageBlockId);
        updateJsonFieid();
    };

    $scope.updateFromGridStack = function()
    {
        pageBlockService.updateGridStackAttributes();
        updateJsonFieid();
    };

    $scope.initializeFromJson = function(json)
    {
        var pageBlocks = JSON.parse(json);
        for(var i = 0; i < pageBlocks.length; i++)
        {
            var pageBlock = pageBlocks[i];
            pageBlock = pageBlockService.add(pageBlock, false);
            attachPageBlockListeners(pageBlock.pageBlockId);
        }
    };

    $scope.handleBlockResponse = function(response)
    {
        if(response.success)
        {
            if(response.blockId)
            {
                pageBlockService.update(response.blockId, response.data);
            }
            else
            {
                var pageBlock = pageBlockService.add(response.data);
                attachPageBlockListeners(pageBlock.pageBlockId);
            }

            updateJsonFieid();
            $('#edit-pageblock-options').modal('hide');
        }
        else
        {
            $('.has-danger').removeClass('has-danger');
            $('.form-control-feedback').remove();

            for(var name in response.errors)
            {
                var errorHtml = '';

                for(var error in response.errors[name])
                {
                    errorHtml += '<div class="form-control-feedback">'+response.errors[name][error]+'</div>';
                }

                var field = $('#fc-' + name);
                if(field)
                {
                    field.after(errorHtml);
                    field.parent().addClass('has-danger');
                }

            }
        }
    }

    var updateJsonFieid = function()
    {
        $scope.json =  JSON.stringify(pageBlockService.getAll());
        $scope.$apply();
    };

    var attachPageBlockListeners = function(pageBlockId)
    {
        $('[data-pageblock-id="'+pageBlockId+'"] .icon-settings').click(function(){
            $scope.editPageBlock(pageBlockId)
        });
        $('[data-pageblock-id="'+pageBlockId+'"] .icon-rubbish').click(function(){
            $scope.remove(pageBlockId)
        });
    };

    var loadOptionsForm = function(url, data)
    {
        $.ajax({
            type: data ? 'POST' : 'GET',
            data: data,
            url:   urlPrefix + url,
            success: function(response, status, xhr){

                //Set content
                $('#edit-pageblock-options .modal-content .container').html(response);

                if($('#edit-pageblock-options .modal-content .container form').length > 0) {

                    //Initalize selectize
                    $('#edit-pageblock-options .modal-content .container .selectize').selectize();

                    //Remove old TinyMce instances
                    for (var i = 0; i < editorCount; i++) {
                        tinymce.execCommand('mceRemoveEditor', false, 'pageBuilderEditor-' + i);
                    }
                    editorCount = 1;

                    //Initialise TinyMce
                    $('#edit-pageblock-options .modal-content .container textarea.rich-text').each(function () {
                        var textArea = $(this);
                        var textAreaId = 'pageBuilderEditor-' + editorCount;
                        textArea.attr('id', textAreaId);
                        tinymce.execCommand('mceAddEditor', false, textAreaId);
                        editorCount++;
                    });

                    //Attach event to close button
                    $('#edit-pageblock-options .modal-content .container button[data-button="close"]').click(function () {
                        $('#edit-pageblock-options').modal('hide');
                        return false;
                    });

                    //Show modal
                    $('#edit-pageblock-options').modal('show');
                }
            }
        })
    };
};
