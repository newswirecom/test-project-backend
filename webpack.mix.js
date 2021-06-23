const mix = require('laravel-mix');

// Enable VueJS
mix.vue();

mix.js('resources/js/app.js',    'public/app.js')
 .sass('resources/css/app.scss', 'public/app.css');

