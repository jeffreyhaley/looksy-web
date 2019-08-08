var coreModule = angular.module('core');

coreModule.controller('MSignupController', [ '$scope', '$state', 'ajax', '$location', 'communicator',
		function($scope, $state, ajax, $location, communicator) {

			$scope.signup = {					
				submitSignup : function(signupform) {
					
					// Start the spinner
					$scope.signup.loading = 1;
					
					var request = {
							module: 'signup',
							action: 'submitSignup',
							data: JSON.stringify($scope.signup)
					}

					var result = ajax.get(request, function(value, responseSuccess) {
						signupform.validation_email_status = value.validation.UserEmail.status;
						signupform.validation_email_message = value.validation.UserEmail.message;
						
						// If successful log in user
						if (value.status === "success")
						{							
							$scope.signup.loginSignup();
						}										
					});	
					
					// Stop the spinner
					$scope.signup.loading = 0;
				},
			
				loginSignup : function() {
					var request = {
							module : 'login',
							action : 'submitLogin',
							data : JSON.stringify($scope.signup)
					}
					
					// Make the request to log in.
					var result = ajax.get(request, function(value, responseSuccess, error) {						
						
						communicator.setData({
							authenticated : value.data.Authenticated,
							useremail : value.data.UserEmail,
							userid : value.data.UserId
						});
	
						if (value.data.Authenticated)
						{						
							// Navigate to the home page.
							$state.go('authenticated.location');
						} 
						
						if (value.status == "error") {				
							$scope.login.message = value.message;	
						}
					});
				}
			}
		} ]);
