var calendarApp = angular.module('calendarApp',['ngSanitize']);
calendarApp.service('calendarService', [calendarService]);
calendarApp.controller("calendarController", [ '$scope', 'calendarService', calendarController]);