var photoAlbumApp = angular.module('photoAlbumApp',['ngSanitize']);
photoAlbumApp.service('photoService', [photoService]);
photoAlbumApp.controller("photoController", [ '$scope', 'photoService', photoController]);