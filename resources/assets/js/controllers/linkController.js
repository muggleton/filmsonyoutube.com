app.controller('linkController', ['$scope', '$routeParams', 'linkService', 'Page', function($scope, $routeParams, linkService, Page){
	$scope.notFound = false;
	$scope.link = [];
	
	$scope.init = function() {
		// Get all links
		var link = linkService.get($routeParams.id);

		// If it is successful
		link.success(function(response){

			$scope.link = response;
			
			if (typeof response.film.title === 'undefined') 
			{
				// Movie not found
				$scope.notFound = true;

			}
			else
			{

				Page.setTitle(response.film.title + ' (' + response.film.year + ')');
				$scope.notFound = false;
			}
		});
	}

	$scope.init();
}]);