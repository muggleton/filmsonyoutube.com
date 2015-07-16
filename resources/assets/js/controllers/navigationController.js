app.controller('navigationController', ['$scope', '$location', function($scope, $location) {
	$scope.isCurrent = function(route) {
		return route === $location.path();
	};
}]);