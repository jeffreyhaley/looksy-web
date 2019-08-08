var coreModule = angular.module('core');

var MHeaderController = coreModule.controller('MHeaderController', ['$scope', '$state', 'ajax', 'communicator', '$location', '$log', 'authenticate',
      function($scope, $state, ajax, communicator, $location, $log, authenticate) {

		$scope.authenticated = authenticate.data.authenticated;
		$scope.useremail = authenticate.data.useremail;
		$scope.userid = authenticate.data.userid;	

		$scope.logout = function() {
			
			communicator.setData({
				authenticated : false,
				useremail : false,
				userid : false
			});
			
			var request = {
					module: 'core',
					action: 'logout',
					data: {}
			}
				
			var result = ajax.get(request, function(value, responseSuccess) {
				$state.go('unauthenticated.signup');
				//window.location.reload();
			});
		}
		
		


} ])

/*
jeffrey.haley@gmail.com
3434343@232.com
*/