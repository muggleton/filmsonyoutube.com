app.controller('linkController', ['$scope', '$routeParams', 'linkService', function($scope, $routeParams, linkService){
	$scope.busy = true;

	$scope.init = function() {
		// Get all links
		var link = linkService.get($routeParams.id);
		
		// If it is successful
		link.success(function(response){
			$scope.link = response;
			$scope.busy = false;
		});
	}

	$scope.init();
}]);