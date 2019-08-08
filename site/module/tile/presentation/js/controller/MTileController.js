var coreModule = angular.module('core');
 
coreModule.controller('MTileController', ['$scope', '$state', 'ajax', 'communicator', '$location', '$log', '$stateParams',
     function($scope, $state, ajax, communicator, $location, $log, $stateParams) {  		
	
	
	    /**
	     * Define the Handler
	     */
		$scope.tileHandler = {
			
			// Get all of the tiles for display
			loadTile: function() {							
				var request = {
						module : 'tile',
						action : 'readTile',
						data : JSON.stringify({'LocationId' : $stateParams.locationId})
				     }
				   
				var result = ajax.get(request, function(value, responseSuccess, error) {
					_.map(value.data, function(tile) {	
						tile.TileCreationTime = moment(tile.TileCreationTime, "YYYYMMDD, h:mm:ss a Z").fromNow();
					})
					
					$scope.tiles = value.data;						
				});	
			},
			
			// Get the full tile
			loadFullTile: function(tileId) {				
				$state.go('authenticated.tile', {tileId: tileId});
			},
			
			rotate: function() {
				
				if (!$scope.tileHandler.degClockwise)
					$scope.tileHandler.degClockwise = 90;
				else
					$scope.tileHandler.degClockwise += 90;
				
				var degStr = "rotate(" + $scope.tileHandler.degClockwise + "deg)";
				var parentElem = angular.element("#imagePreviewContainer");
				var imgElem = parentElem.find("img");
				imgElem.css({
					'transform' : degStr,
					'-ms-transform' : degStr,
					'-webkit-transform' : degStr
				});
				
			},
			
			// Submit the tile changes
			submitTile: function(tileform, action, tile) {
				if (tileform.$valid){
					
					// Set the loading flag
					$scope.tileHandler.loading = true;
					
					// Set the location Id
					tile.locationId = $stateParams.locationId | tile.LocationId;
					tile.rotateImage = $scope.tileHandler.degClockwise;
					
					var request = {
							module : 'tile',
							action : action + 'Tile',
							data : JSON.stringify(tile)
					}
   
					var result = ajax.save(request, function(value, responseSuccess, error) { 
						// Remove the loading status
						$scope.tileHandler.loading = false;						
						
						// If the save was successful, remove the modal and reload the tiles.
						if (value.status == "success") {
							
							// Get the modal id
							var modalId = (action === 'create' ? '#modal-tile-new' : '#modal-tile-' + tile.TileId);
							$(modalId).modal('hide');
							
							// When the modal is hidden, reload the tiles.
							$(modalId).on('hidden.bs.modal', function () {
								// Reload tiles
								var request = {
										module : 'tile',
										action : 'readTile',
										data : JSON.stringify({'LocationId' : $stateParams.locationId})
								     }
								   
								var result = ajax.get(request, function(value, responseSuccess, error) {
									$scope.tileHandler.loadTile();
								});
							});
							
							// If deleting just re-load tiles
							if (action == 'delete') {
								$scope.tileHandler.loadTile();
							}
													
							// Clear the tile modal
							$scope.tile = false;
							$('.fileinput').fileinput('clear');
							$scope.tileHandler.showError = false;							
						}
						
						// Alert success or error back to the user
						require(["./common/widget/alert/view/js/model/widget.alert.bo"], function (AlertWidget) {
							var aw = new AlertWidget();            
							aw.setSelector('#header-message');
							aw.initialize({message: 'test', status: 'success'});
							aw.addMessage(value);
						});
						
					});
				} else {
					$scope.tileHandler.showError = true;
				}
			}
       }

		// Load tiles on load.
		$scope.tileHandler.loadTile();	
} ]);
