const path = require('path');
const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */



mix.webpackConfig(webpack => {
    return {
     plugins: [
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery'
        })
      ]
    }
});

mix.alias({
   'Util' : path.join(__dirname, 'resources/js/Utility/Util')
});

mix.postCss('resources/sass/guest.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);

mix.sass('resources/sass/app.scss', 'public/css');
mix.sass('resources/sass/vendors/lte-core.scss', 'public/css/vendors');

mix.js('resources/js/vendors/lte-core.js', 'public/js/vendors');
mix.js('resources/js/guest.js', 'public/js');

mix.js('resources/js/app.js', 'public/js').vue().extract(['jquery', 'lodash', 'vue', 'alpinejs']).sourceMaps().version();

