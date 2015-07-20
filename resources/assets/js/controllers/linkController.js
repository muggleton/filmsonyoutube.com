app.controller('linkController', ['$scope', '$routeParams', 'linkService', 'Page', function($scope, $routeParams, linkService, Page){
	$scope.notFound = false;
	$scope.link = [];
	
	$scope.init = function() {
		// Get all links
		var link = linkService.get($routeParams.id);

		// If it is successful
		link.success(function(response){

			$scope.link = response;
			
			if($scope.link.length)
			{
				// Movie found
				console.log('found');
				Page.setTitle(response.film.title + ' (' + response.film.year + ')');
			}
			else
			{
				$scope.notFound = true;
			}
		});
	}

	$scope.init();
}]);