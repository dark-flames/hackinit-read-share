var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/dist/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/dist')
    // the public path you will use in Symfony's asset() function - e.g. asset('dist/some_file.js')
    .setManifestKeyPrefix('dist/')

    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())

    // the following line enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    //.addEntry('js/app', './assets/js/app.js')
    //.addStyleEntry('css/app', './assets/css/app.scss')

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use Sass/SCSS files
    //.enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    //.autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
