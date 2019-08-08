var coreModule = angular.module('core');

coreModule.run(function ($rootScope, $state, ajax, $log) {
//	 $log.error('here');
//	 var request = {
//				module: 'core',
//				action: 'loadUser',
//				data: {}
//		}
//		   var x = {};
//		var result = ajax.get(request, function(value, responseSuccess, error) {
//			 //$log.error($rootScope);
//			var x = value.data;		
//
//			    $rootScope.$on("$stateChangeStart", function(event, toState, toParams, fromState, fromParams){
//			      if (toState.authenticate && (!x.authenticated || typeof x.authenticated == 'undefined')){
//			    	  event.preventDefault(); 
//			    	  // User isn’t authenticated
//			    	  $state.go('unauthenticated.signup');
//			      }
//			      
//		});	
//
//    })
 })

.config([ '$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {

	//$urlRouterProvider.when('/home', '/location');
	$urlRouterProvider.otherwise("/signup");


	  // Now set up the states
	  $stateProvider
	  .state('unauthenticated',{
		  	abstract: true,
		    url: '',
		    templateUrl:  './module/core/presentation/template/MCoreUnauthenticated.html'
		  })
		 .state('unauthenticated.signup',{
			 url: '/signup',
			 resolve: {
				 authenticate: function(ajax){
						var request = {
								module: 'core',
								action: 'loadUser',
								data: {}
						}
						
					 	return ajax.get(request).$promise;
					 }			
			 },
			 views: {
			      'header': {
			    	  templateUrl: './module/core/presentation/template/MCoreHeader.html',
			    	  controller: 'MHeaderController'
			      },
			      'signup': {
				      templateUrl: './module/signup/presentation/template/MSignup.html',
				      controller: 'MSignupController'
				        	
				  }
			    }
			  })		  
			  
	   .state('authenticated',{
		  	abstract: true,
		    url: '',
		    authenticate: true,
		    templateUrl:  './module/core/presentation/template/MCore.html'
		  })
	 .state('authenticated.location',{
		 url: '/location',
		 authenticate: true,
		 resolve: {
			 authenticate: function(ajax){	
					var request = {
							module: 'core',
							action: 'loadUser',
							data: {}
					}
					
				 	return ajax.get(request).$promise;
			 },
			 locations: function(ajax){					
					var request = {
							module: 'location',
							action: 'readLocation',
							data: {}
					}
					
				 	return ajax.get(request).$promise;
			 }
		 },
		 views: {
		      'header': {
		    	  templateUrl: './module/core/presentation/template/MCoreHeader.html',
		          controller: 'MHeaderController'
		      },
		      'content': {
			      templateUrl: './module/location/presentation/template/MLocation.html',
			      controller: 'MLocationController'
			  },
			  'footer': {
				  templateUrl: './module/core/presentation/template/MCoreFooter.html'
			  }
		    }
		  })	  
		  
	 .state('authenticated.tile', {
		 url: '/tile/:tileId',
		 //authenticate: true,
		 resolve: {
			 authenticate: function(ajax){	
					var request = {
							module: 'core',
							action: 'loadUser',
							data: {}
					}
					
				 	return ajax.get(request).$promise;
			 },
			 tile: function(ajax, $stateParams){
				 var request = {
			  			module : 'tile',
				        action : 'readTile',
				        data : JSON.stringify({'TileId' : $stateParams.tileId})
				     }
				      
				 return ajax.get(request).$promise; 
			  }
		 },
		 views: {
		      'header': {
		    	  templateUrl: './module/core/presentation/template/MCoreHeader.html',
		          controller: 'MHeaderController'
		      },
		      'content': {
		    	  templateUrl: './module/tile/presentation/template/MTileFull.html',
					 controller: 'MTileFullController'
			  },
			  'footer': {
				  templateUrl: './module/core/presentation/template/MCoreFooter.html'
			  }
		    }
		  })		
		  .state('authenticated.location.tile', {
		      url: '/:locationId/tile',
				 resolve: {
					 //authenticate: function(authenticate){return authenticate.$promise},
					 //tiles: function(tiles){return tiles.$promise}
				 },
		      templateUrl: './module/tile/presentation/template/MTile.html',
		      authenticate: true
		    })	
		  

		
//	    .state('tile', {
//			 url: '/tile/:tileId',
//			 resolve: {	
//				 tile: function(ajax, $stateParams){
//					 var request = {
//				  			module : 'tile',
//					        action : 'readTile',
//					        data : JSON.stringify({'TileId' : $stateParams.tileId})
//					     }
//					      
//					 return ajax.get(request).$promise; 
//				  }
//			 },			 
//			 templateUrl: './module/tile/presentation/template/MTileFull.html',
//			 controller: 'MTileFullController'
//		 })


	    
} ])