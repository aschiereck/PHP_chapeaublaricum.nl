
var moduleService = function() {

    var modules = [];

    var add = function(module)
    {
        modules.push(module);
    };

    var getAll = function()
    {
       return modules;
    };

    var getByModel = function(model)
    {
        for(var i = 0; i < modules.length; i++)
        {
            var module = modules[i];
            if(module.model == model)
            {
                return module;
            }
        }
    };

    var getByTemplate = function(template)
    {
        for(var i = 0; i < modules.length; i++)
        {
            var module = modules[i];
            if(module.template == template)
            {
                return module;
            }
        }
    };

    return {
        add: add,
        getAll: getAll,
        getByModel: getByModel,
        getByTemplate : getByTemplate
    };
};