app.controller('navigationController', function($scope, $location) {
	$scope.isCurrent = function(route) {
		return route === $location.path();
	};
});