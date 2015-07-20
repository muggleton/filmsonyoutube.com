// Declare app and dependancies
var app = angular.module('filmsonyoutube', ['ngRoute', 'angular-loading-bar', 'infinite-scroll', 'angular-accordion'], ['$interpolateProvider', '$sceDelegateProvider', 'cfpLoadingBarProvider', function($interpolateProvider, $sceDelegateProvider, cfpLoadingBarProvider) {
	$interpolateProvider.startSymbol('<%');
	$interpolateProvider.endSymbol('%>');
	// Whitelist Youtube URL
	$sceDelegateProvider.resourceUrlWhitelist([ 'self','*://www.youtube.com/**']);

	// Don't show loading bar spinner
	cfpLoadingBarProvider.includeSpinner = false;

	// Infinite scroll performance issues
	angular.module('infinite-scroll').value('THROTTLE_MILLISECONDS', 250)

}]);


app.run(function(){

});

 // Handle our routing
 app.config(['$routeProvider', '$locationProvider', function($routeProvider, $locationProvider){
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
 }]);
