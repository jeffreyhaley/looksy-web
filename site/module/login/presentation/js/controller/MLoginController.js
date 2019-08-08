var coreModule = angular.module('core');

coreModule.controller('MLoginController', ['$scope', '$state', 'ajax', 'communicator', '$location', '$log',  
    function($scope, $state, ajax, communicator, $location, $log) {

	$scope.login = {
		submitLogin : function(loginform) {

			var request = {
				module : 'login',
				action : 'submitLogin',
				data : JSON.stringify($scope.login)
			}

			// Remove the loading status
			$scope.login.loading=true;
			$log.error('state-go0');
			// Make the request to log in.
			var result = ajax.get(request, function(value, responseSuccess, error) {
				$log.error('state-go1');
				// Remove the loading status
				$scope.login.loading=false;

				communicator.setData({
					authenticated : value.data.Authenticated,
					useremail : value.data.UserEmail,
					userid : value.data.UserId
				});
				$log.error('state-go2');

				if (value.data.Authenticated)
				{
					$log.error('state-go3');
					// Hide the login modal
					$('#modal-login').modal('hide');				
					
					$('#modal-login').on('hidden.bs.modal', function () {					
						// Navigate to the home page.
						$log.error('state-go4');
						$state.go('authenticated.location');
					});
				} 
				
				if (value.status == "error") {				
					$scope.login.message = value.message;	
				}
			});
		}
	}
} ]);