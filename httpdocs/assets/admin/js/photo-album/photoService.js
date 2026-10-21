/**
 * @returns {{addPhoto: Function, remove: Function}}
 */
var photoService = function() {

    var photos = [];

    /**
     * @param id
     * @param url
     * @param name
     * @param position
     */
    var add = function(id, url, name, position)
    {
        photos.push({
            "id" : id,
            "url" : url,
            "name" : name,
            "position" : parseFloat(position)
        })
    };

    /**
     * @param id
     */
    var remove = function(id)
    {
        for(var i = 0; i < photos.length; i++)
        {
            var photo = photos[i];
            if(photo.id == id)
            {
                photos.splice(i,1);
                return;
            }
        }
    };

    var getPhotos = function()
    {
        return photos;
    };

    var updatePositions = function(positions)
    {
        for(var i = 0; i < positions.length; i++)
        {
            var id = positions[i];
            for(var j = 0; j < photos.length; j++)
            {
                var photo = photos[j];
                if(photo.id == id)
                {
                    photos[j].position = i;
                    break;
                }
            }
        }
    };

    return {
        addPhoto: add,
        remove: remove,
        getPhotos : getPhotos,
        updatePositions : updatePositions
    };
};