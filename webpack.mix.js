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
   .sass('resources/sass/app.scss', 'public/css')
   .js('resources/js/tinymce.js', 'public/js')
   .copy('node_modules/tinymce/skins', 'public/js/skins')
   .copy('node_modules/tinymce/themes', 'public/js/themes')
   .copy('node_modules/tinymce/icons', 'public/js/icons')
   .copy('node_modules/tinymce/plugins', 'public/js/plugins')
   ;
