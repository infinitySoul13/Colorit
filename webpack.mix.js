const mix = require('laravel-mix');

require('laravel-mix-alias');

mix.alias({
    '~': '/resources/js',
});
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
   .sass('resources/sass/app.scss', 'public/css');
// mix.js('resources/js/app.js', 'public/js')
//     .vue()
//     .sass('resources/sass/app.scss', 'public/css');

// mix.js('resources/js/app.js', 'public/js')
//     .version();
//
// mix.sass('resources/sass/app.scss', 'public/css')
//     .version();

// mix.copyDirectory('resources/assets', 'public/assets');

// mix.alias({
//     '@': 'resources/assets/js',
//     '~': 'resources/assets/sass',
//     '@components': 'resources/assets/js/components',
// });
