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
    .js('resources/assets/js/views/general.form.js', 'public/js')
    .js('resources/assets/js/views/drivers.js', 'public/js')
    //.extract(['jquery', 'axios', 'vue', 'bootstrap-sass'], 'public/js');

mix.sass('resources/assets/sass/app.scss', 'public/css')

if (mix.inProduction()) {
    mix.version();
}

// > php artisan serve
mix.browserSync("http://127.0.0.1:8000"); // tu url de localhost
