let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
<<<<<<< HEAD
   .sass('resources/assets/sass/app.scss', 'public/css')
   .sass('public/bmtmudathemes/assets/sass/main.scss', 'public/bmtmudathemes/assets/css');
=======
   .sass('resources/assets/sass/app.scss', 'public/css');
>>>>>>> alam/BMTMuda/development
