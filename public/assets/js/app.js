// Declare app and dependancies
var app = angular.module('filmsonyoutube', ['ngRoute', 'angular-loading-bar', 'infinite-scroll'], function($interpolateProvider, $sceDelegateProvider, cfpLoadingBarProvider, $logProvider) {
	$interpolateProvider.startSymbol('<%');
	$interpolateProvider.endSymbol('%>');
	// Whitelist Youtube URL
	$sceDelegateProvider.resourceUrlWhitelist([ 'self','*://www.youtube.com/**']);

	// Don't show loading bar spinner
	cfpLoadingBarProvider.includeSpinner = false;

	// Enable debugging
	$logProvider.debugEnabled(true);

	// Infinite scroll performance issues
	angular.module('infinite-scroll').value('THROTTLE_MILLISECONDS', 250)

});


app.run(function(){

});

 // Handle our routing
 app.config(function($routeProvider, $locationProvider){
 	// When on home route
 	$routeProvider.when('/',{
 		// Load index template
 		templateUrl: '/views/links.html',
 		// Use the Link controller
 		controller: 'linksController'
 	});

 	// When on film route
 	$routeProvider.when('/:id/:title',{
 		// Load index template
 		templateUrl: '/views/link.html',
 		// Use the Link controller
 		controller: 'linkController'
 	});

 	// use the HTML5 History API
 	$locationProvider.html5Mode(true);
 });

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
app.controller('linksController', function($rootScope, $scope, $q,  $filter, linkService, sidebarService){
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


});

app.controller('navigationController', function($scope, $location) {
	$scope.isCurrent = function(route) {
		return route === $location.path();
	};
});


app.service('linkService', function($http){
	var search_results = [];

	return {
		all: function(page, search_term, genres, resolution, languages, rating_min, rating_max, year_from, year_to){
			var request = $http({method:'GET', url:'/api/v1/links?page=' + page + '&search=' + search_term + '&genres=' + genres +  '&resolution=' + resolution + '&languages=' + languages + '&rating=' + rating_min + ',' + rating_max + '&year=' + year_from + ',' + year_to});
			return request;
		},

		get: function(id){
			var request = $http({method:'GET', url:'/api/v1/links/' + id});
			return request;
		}
	}
});
app.service('sidebarService', function($http, $q){
	return {
		all: function() {
                // the $http API is based on the deferred/promise APIs exposed by the $q service
                // so it returns a promise for us by default
                return $http.get('/api/v1/sidebar')
                .then(function(response) {
                	if (typeof response.data === 'object') {
                		return response.data;
                	} else {
                            // invalid response
                            return $q.reject(response.data);
                        }

                    }, function(response) {
                        // something went wrong
                        return $q.reject(response.data);
                    });
            }
        };
    });
//# sourceMappingURL=app.js.map