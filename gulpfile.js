var elixir = require('laravel-elixir');
var cssimport = require("gulp-cssimport");
var gulp = require('gulp');
var autoprefixer = require('gulp-autoprefixer');
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var minifyCss = require('gulp-minify-css');
var gulpif = require('gulp-if');


// Are we in production?! 
var inProduction = elixir.config.production;

function swallowError (error) {

    //If you want details of the error in the console
    console.log(error.toString());

    this.emit('end');
}

elixir.extend('mysass', function(src, name, output, options) {

	gulp.task('mysass', function() {
		gulp.src(src)
		.pipe(cssimport({matchPattern: "*.css"}))
		.on('error', swallowError)
		.pipe(sass(options))
		.pipe(autoprefixer({
			browsers: ['last 2 versions'],
			cascade: false
		}))
		// Check whether we are in production
		// if we are -- minify
		.pipe(gulpif(inProduction, minifyCss()))
		.pipe(concat(name))
		.pipe(gulp.dest(output));
	});

	return this.queueTask('mysass');

});

var paths = {
	'bootstrap': './resources/assets/bower/bootstrap-sass-official/assets/',
	'angular_route': './resources/assets/bower/angular-route/',
}

elixir(function(mix) {
	mix.registerWatcher('mysass', '**/*.scss')
	.mysass('resources/assets/sass/app.scss', 'app.css', 'public/assets/css/', {includePaths: [paths.bootstrap + 'stylesheets/']})
	// Copy fonts to public
	.copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/assets/fonts/bootstrap')
	// Copy angular route as it needs to be kept seperate from angular...
	.copy(paths.angular_route + 'angular-route.min.js', 'public/assets/js/vendor/angular-route.min.js')
	// Combine vendor/angular and angular vendor scripts
	.scripts([

		'bootstrap-sidebar/dist/js/sidebar.js',
		'jquery/dist/jquery.min.js',
		'bootstrap-sass-official/assets/javascripts/bootstrap.min.js',
		'angular/angular.js', 
		'angular-loading-bar/src/loading-bar.js',
		'ngInfiniteScroll/build/ng-infinite-scroll.min.js',
		'angularjs-slider/dist/rzslider.min.js',
		'ng-debounce/angular-debounce.js'
		], 'public/assets/js/vendor/vendor.js' ,'resources/assets/bower')
	// Combine all scripts (controllers, filters etc) in to one app.js file
	.scriptsIn(['resources/assets/js'], 'public/assets/js/app.js')
});