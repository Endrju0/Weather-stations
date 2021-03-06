const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/geolocation-map-center.js', 'public/js')
   .js('resources/js/generate-chart.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .sass('resources/sass/dark_theme/app.scss', 'public/css/dark_theme.css');
