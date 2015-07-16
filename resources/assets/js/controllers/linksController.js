app.controller('linksController', ['$rootScope', '$scope', '$q', '$filter', 'linkService', 'sidebarService', function($rootScope, $scope, $q,  $filter, linkService, sidebarService){
	$scope.links = [];
	$scope.current_page = 1;
	$scope.last_page = 0;
	$scope.busy = false;
	$scope.search_term = '';
	$scope.sidebar = [];
	$scope.sidebar.rating = [];
	$scope.range = [];

	$scope.genres_query_string = '';
	$scope.languages_query_string = '';
	$scope.resolutions_query_string = '';


	$scope.init = function() {
		$scope.busy = true;

		sidebarService.all()
		.then(function(data){
			$scope.sidebar = data;
		}, function(error) {
			console.log('error', error);
		});

	// Get all links
	var links = linkService.all(1, $scope.search_term, $scope.genres_query_string, $scope.resolutions_query_string, $scope.languages_query_string,  0, 10, 0, 10000);

		// If it is successful
		links.success(function(response){
			$scope.current_page = response.current_page;
			$scope.last_page = response.last_page;
			$scope.links = response.data;


		});

		$scope.busy = false;

	}
	$scope.loadMore = function()
	{
		if(typeof $scope.sidebar.rating.from === 'undefined') return;
		if($scope.busy === true) return;
		if($scope.current_page == $scope.last_page) return;

		$scope.busy = true;

		var links = linkService.all($scope.current_page + 1, $scope.search_term, $scope.genres_query_string, $scope.resolutions_query_string, $scope.languages_query_string, $scope.sidebar.rating.from, $scope.sidebar.rating.to, $scope.sidebar.year.from, $scope.sidebar.year.to);
		// If it is successful
		links.success(function(response){
			// For some reason we can't push the entire array to the scope so we will do it one by one
			for (var i = 0; i < response.data.length; i++) {
				$scope.links.push(response.data[i]);
			}
			$scope.current_page = response.current_page;
			$scope.last_page = response.last_page;
			

		});
		$scope.busy = false;
	}



	$scope.filterLinks = function() {

		$scope.busy = true;

		// Genres
		$scope.sidebar.selected_genres = $filter('filter')($scope.sidebar.genres, {checked: true});
		$scope.genres_query_string = '';
		for (var i = 0; i < $scope.sidebar.selected_genres.length; i++) {
			$scope.genres_query_string += ',' + $scope.sidebar.selected_genres[i].id;
		}

		// Resolution
		$scope.sidebar.selected_resolutions = $filter('filter')($scope.sidebar.resolutions, {checked: true});
		$scope.resolutions_query_string = '';
		for (var i = 0; i < $scope.sidebar.selected_resolutions.length; i++) {
			$scope.resolutions_query_string += ',' + $scope.sidebar.selected_resolutions[i].id;
		}

		// Resolution
		$scope.sidebar.selected_languages = $filter('filter')($scope.sidebar.languages, {checked: true});
		$scope.languages_query_string = '';
		for (var i = 0; i < $scope.sidebar.selected_languages.length; i++) {
			$scope.languages_query_string += ',' + $scope.sidebar.selected_languages[i].id;
		}

		// Get all links
		var links = linkService.all(1, $scope.search_term, $scope.genres_query_string, $scope.resolutions_query_string, $scope.languages_query_string, $scope.sidebar.rating.from, $scope.sidebar.rating.to, $scope.sidebar.year.from, $scope.sidebar.year.to);

		// If it is successful
		links.success(function(response){
			$scope.current_page = response.current_page;
			$scope.last_page = response.last_page;
			$scope.links = response.data;

		});
		$scope.busy = false;
	};


	$scope.init();


}]);
