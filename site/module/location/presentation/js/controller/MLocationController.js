var coreModule = angular.module('core');

var MLocationController = coreModule.controller('MLocationController', ['$scope', '$state', 'ajax', 'communicator', '$location', '$log', 'locations',
      function($scope, $state, ajax, communicator, $location, $log, locations) {
		
	
	
		/**
		 *  The location handler handles the CRUD operations
		 */
		$scope.locationHandler = {	
				loadLocation: function(locations) {
					$scope.loaded = false;
					
					if (typeof locations.data[0] != 'undefined') {
						$scope.location = locations.data[0];
						$scope.loaded = true;
				  	
						// Navigate to the tiles for location.
						$state.go('authenticated.location.tile', {locationId: $scope.location.LocationId});
					}
					else {
						
						$state.go('authenticated.location');
					}
					
					// Get all of the states for the dropdown
					var request = {
					  module : 'location',
					  action : 'readState',
					  data : {}
			        }
			      
			        var result = ajax.get(request, function(value, responseSuccess, error) {
			          $scope.states = value.data;
			        });
				},
				
			// Submit the location data
			submitLocation: function(locationform, action, location) {

				if (locationform.$valid){
					
					// Set the loading indicator
					$scope.locationHandler.loading = true;	

					var request = {
						module : 'location',
						action : action + 'Location',
						data : JSON.stringify(location)
					}
					
					var result = ajax.save(request, function(value, responseSuccess, error) {
						$scope.locationHandler.loading = true;	
						locationform.validation_LocationName_status = value.validation.LocationName.status;
						locationform.validation_LocationName_message = value.validation.LocationName.message;

						locationform.validation_LocationZip_status = value.validation.LocationZip.status;
						locationform.validation_LocationZip_message = value.validation.LocationZip.message;	
						
						// Alert success or error back to the user
						require(["./common/widget/alert/view/js/model/widget.alert.bo"], function (AlertWidget) {
							var aw = new AlertWidget();						
							aw.setSelector('#header-message');
							aw.initialize({message: 'test', status: 'success'});
							aw.addMessage(value);									
						});
					
						// Remove the loading status
						$scope.locationHandler.loading = false;
						
						// If successful, hide the modal and update the locations.
						if (value.status === 'success')
						{
							var modalId = (action === 'create' ? '#modal-location-new' : '#modal-location-' + location.LocationId);
							$(modalId).modal('hide');
							
							$(modalId).on('hidden.bs.modal', function () {
								// Refresh the locations
								var request = {
							          module : 'location',
							          action : 'readLocation',
							          data : {}
							    }

						        var result = ajax.get(request, function(value, responseSuccess, error) {
						        	// geocode the address
						        	var locationId = value.data[0].LocationId;
									var address = location.LocationAddress + ", " + location.LocationCity + ", " + location.USStateAbbr;
									var geocoder = new google.maps.Geocoder();
									geocoder.geocode( { 'address': address}, function(results, status) {
										if (status == google.maps.GeocoderStatus.OK) {
											var lat = results[0].geometry.location.d;
											var lng = results[0].geometry.location.e;
											var request = {
												module: 'location',
												action: 'updateGeoLocation',
												data: {
													LocationId: locationId,
													LocationLat: lat,
													LocationLng: lng
												}
											};
											var result = ajax.get(request, function(value, responseSuccess, error) {
												// errors ignored
											});
											
										} else {
											// errors ignored
										}
									});
						        	
						        	$scope.locationHandler.loadLocation(value);
						        });									
							});
							$scope.loaded = true;
						}
					});
					
				} else {
					$scope.locationHandler.showError = true;
				}
			},
			
			 /**
			  * Deletes the current location.
			  */
			deleteLocation : function(locationId) {
				var request = {
					module : 'location',
					action : 'deleteLocation',
					data : JSON.stringify({'locationId' : locationId})
				}

				// Set the loading status
				$scope.locationHandler.loading=true;
				
				var result = ajax.save(request, function(value, responseSuccess, error) {

					if (value.status === 'success')
					{
						// Refresh the locations
						var request = {
					          module : 'location',
					          action : 'readLocation',
					          data : {}
					        }
					      
				        var result = ajax.get(request, function(value, responseSuccess, error) {
				        	$scope.location = null;
				          	
				    		// Navigate to the tiles for location.
				        	$state.go('authenticated.location');
				        });
					}
					
					// Alert success or error back to the user
					require(["./common/widget/alert/view/js/model/widget.alert.bo"], function (AlertWidget) {
						var aw = new AlertWidget();						
						aw.setSelector('#header-message');
						aw.initialize({message: 'test', status: 'success'});
						aw.addMessage(value);									
					});

					// Remove the loading status
					$scope.locationHandler.loading=false;
					$scope.loaded = false;
				});				
			}
		}
		
		$scope.locationHandler.loadLocation(locations);


} ])