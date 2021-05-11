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

mix.setPublicPath('../smartii.cc')
    .js('resources/js/app.js', '../smartii.cc/js')
    .sass('resources/sass/app.scss', '../smartii.cc/css')
    .sourceMaps();