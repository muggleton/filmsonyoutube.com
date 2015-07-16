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
	'jquery': './resources/assets/bower/jquery/',
	'bootstrap': './resources/assets/bower/bootstrap-sass-official/assets/',
	'angular': './resources/assets/bower/angular/',
	'angular_loading_bar': './resources/assets/bower/angular-loading-bar/src/',
	'angular_timeago': './resources/assets/bower/angular-timeago/src/',
	'angular_route': './resources/assets/bower/angular-route/',
	'bootstrap_sidebar': './resources/assets/bower/bootstrap-sidebar/',
	'bootstrap_slider': './resources/assets/bower/seiyria-bootstrap-slider/dist/'
}

elixir(function(mix) {
	mix.registerWatcher('mysass', '**/*.scss')
	.mysass('resources/assets/sass/app.scss', 'app.css', 'public/assets/css/', {includePaths: [paths.bootstrap + 'stylesheets/']})
	.copy(paths.bootstrap_sidebar + '/dist/js/sidebar.js', 'public/assets/js/vendor/sidebar.js')
	// Copy fonts to public
	.copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/assets/fonts/bootstrap')
	// Copy bootstrap dependancy
	.copy(paths.jquery + 'dist/jquery.min.js', 'public/assets/js/vendor/jquery.min.js')
	// Copy bootstrap dependancy
	.copy(paths.bootstrap +  'javascripts/bootstrap.min.js', 'public/assets/js/vendor/bootstrap.min.js')
	.copy(paths.bootstrap_slider + 'bootstrap-slider.min.js', 'public/assets/js/vendor/slider.js')
	// Copy angular route as it needs to be kept seperate from angular...
	.copy(paths.angular_route + 'angular-route.min.js', 'public/assets/js/vendor/angular-route.min.js')
	// Combine angular and angular vendor scripts
	.scripts([
		'angular/angular.js', 
		'angular-loading-bar/src/loading-bar.js',
		'ngInfiniteScroll/build/ng-infinite-scroll.min.js',
		'angularjs-slider/dist/rzslider.min.js',
		'ng-debounce/angular-debounce.js'
		], 'public/assets/js/vendor/angular.js' ,'resources/assets/bower')
	// Combine all scripts (controllers, filters etc) in to one app.js file
	.scriptsIn(['resources/assets/js'], 'public/assets/js/app.js')
});