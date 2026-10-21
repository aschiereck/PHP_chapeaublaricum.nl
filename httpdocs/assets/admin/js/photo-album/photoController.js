
var photoController =  function( $scope, photoService) {

    $scope.photos = photoService.getPhotos();

    $scope.remove = function(id)
    {
        photoService.remove(id);
    };

    $scope.add = function(id, url, name, position)
    {
        photoService.addPhoto(id, url, name, position);
        $scope.$apply();
    };

    $scope.updatePositions = function(positions)
    {
        photoService.updatePositions(positions);
        $scope.$apply();
    }
};
