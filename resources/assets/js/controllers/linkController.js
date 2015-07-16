app.controller('linkController', function($scope, $routeParams, linkService){

	$scope.init = function() {
		// Get all links
		var link = linkService.get($routeParams.id);

		// If it is successful
		link.success(function(response){
			$scope.link = response;
		});
	}

	$scope.init();
});