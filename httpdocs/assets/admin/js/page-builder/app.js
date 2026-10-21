var pageBuilderApp = angular.module('pageBuilderApp',['ngSanitize']);
pageBuilderApp.service('moduleService', [moduleService]);
pageBuilderApp.service('pageBlockService', ['moduleService', pageBlockService]);
pageBuilderApp.controller("pageBuilderController", [ '$scope', 'pageBlockService', 'moduleService', pageBuilderController]);