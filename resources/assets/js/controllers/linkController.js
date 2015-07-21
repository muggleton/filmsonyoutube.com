app.controller('linkController', ['$scope', '$routeParams', 'linkService', 'Page', function($scope, $routeParams, linkService, Page){
	$scope.notFound = false;
	$scope.link = [];
	
	$scope.init = function() {
		// Get all links
		var link = linkService.get($routeParams.id);

		// If it is successful
		link.success(function(data, status){
			$scope.link = data;
			
			if (status == 200) 
			{
				Page.setTitle(data.film.title + ' (' + data.film.year + ')');
			}
			// No content status code
			else if(status == 204)
			{
				$scope.notFound = true;
			}
		});

		link.error(function(data){
			$scope.notFound = true;
		});
	}

	$scope.init();
}]);