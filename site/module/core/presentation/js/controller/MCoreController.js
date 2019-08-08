'use strict';

angular.module('core', [ 'ngResource', 'ui.mask', 'ui.router', 'ui.bootstrap', 'ngAnimate' ])

.controller('MCoreController', [ '$scope', '$resource', 'communicator', 'ajax', '$log', '$modal', 'authenticate',
   function($scope, $resource, communicator, ajax, $log, $modal, authenticate) {
		
		// Get all of the states for the dropdown
		var request = {
		  module : 'core',
		  action : 'loadUser',
		  data : {}
        }
      
        var result = ajax.get(request, function(value, responseSuccess, error) {
          $scope.authenticated = value.authenticated;
        });
	
} ])

.factory('ajax', [ '$resource', function($resource) {
	return $resource('./index.php/:request', {
		request : '@request'
	});
} ])

.factory('communicator', [ '$rootScope', function($rootScope) {
	
	var obj = {};
	
	obj.data = {
		authenticated: false
	};
	
	obj.setData = function (data) {
		this.data = data;
		this.broadcast();
	};
	
	obj.broadcast = function () {
		$rootScope.$broadcast('broadcast');
	};
	
	return obj;
} ])

.service('locations', [ 'ajax', function(ajax) {
  	var request = {
  			module : 'location',
	        action : 'readLocation',
	        data : {}
	     }
	      
  	 return ajax.get(request);      
	
} ])

.service('tiles', [ 'ajax', function(ajax) {
  	var request = {
  			module : 'tile',
	        action : 'readTile',
	        data : {}
	     }
	      
  	 return ajax.get(request);      
	
} ])


