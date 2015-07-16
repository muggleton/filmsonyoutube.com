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