const mix = require( 'laravel-mix' );

mix.options( {
	processCssUrls: false
} )
	.sass( 'assets/sass/editor-style.scss', './' )
	.sass( 'assets/sass/style.scss', './' )
	.minify( [
		'assets/js/scripts.js'
	] )
