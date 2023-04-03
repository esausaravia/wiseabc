const mix = require('laravel-mix');
mix.disableNotifications();

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

mix.js('resources/js/app.js', 'public/js')
    //.js('resources/js/fontawesome.js','public/js')
    .js('resources/js/admin.classroom.cforcurso.js','public/js')
    .css('resources/css/app.css', 'public/css')
    .options({
      processCssUrls: false,
      postCss: [
        require('postcss-import'),
        require('tailwindcss/nesting'),
      ]

    })
    .sourceMaps();


// postCss: [
//   process.env.NODE_ENV === 'production' ? require('@fullhuman/postcss-purgecss')({
//     content:[
//       './resources/views/*.{html,php}',
//       './resources/views/**/*.{html,php}'
//     ],
//     defaultExtractor: content => content.match(/[^<>"'`\s]*[^<>"'`\s:]/g) || [],
//   }) : null
// ]