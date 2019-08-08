var coreModule = angular.module('core');
 
coreModule.controller('MTileFullController', ['$scope', '$state', 'ajax', 'communicator', '$location', '$log', 'tile',
     function($scope, $state, ajax, communicator, $location, $log, tile) {  		
		if (typeof tile.data[0] != 'undefined') {
			$scope.tile = tile.data[0];
			
			$scope.tile.TileCreationTime = moment(tile.data[0].TileCreationTime, "YYYYMMDD, h:mm:ss a Z").fromNow();
		}
} ]);