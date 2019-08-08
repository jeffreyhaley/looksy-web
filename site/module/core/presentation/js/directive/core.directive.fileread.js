var coreModule = angular.module('core');

coreModule.directive("fileread",  [function () {
	 return {
	        scope: {
	            fileread: "="
	        },
	        require: 'ngModel',
	        link: function (scope, el, attrs, ngModel) {
//	        	ngModel.$render = function () {
//	                ngModel.$setViewValue(el.val());
//	            };
	            el.bind("change", function (changeEvent) {
	                var reader = new FileReader();
	                reader.onload = function (loadEvent) {
	                    scope.$apply(function () {
	                    	//ngModel.$render();
	                        scope.fileread = loadEvent.target.result;
	                    });
	                }
	                reader.readAsDataURL(changeEvent.target.files[0]);
	            });
	        }
	    }
}]);