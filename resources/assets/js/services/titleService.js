app.factory('Page', function(){
	var title = 'FilmsOnYoutube';
	var after = ' - FilmsOnYoutube';
	return {
		title: function() { return title; },
		setTitle: function(newTitle) { title = newTitle + after; }
	};
});