let Encore = require('@symfony/webpack-encore');

Encore

    .setOutputPath('public/resources/build/')
    .setPublicPath('/resources/build')
    .cleanupOutputBeforeBuild()
    .addEntry('app', ['babel-polyfill', 'whatwg-fetch', './assets/app.js'])

    // Babel
    .configureBabel(function(babelConfig) {
        babelConfig.presets.push('react');
        babelConfig.plugins.push('transform-class-properties');
    })
    .enableReactPreset()

    // Style
    .addStyleEntry('style/css/style', './assets/scss/style.scss')
    .enableSassLoader()

    // Legacy jQuery
    .autoProvidejQuery()

    // Environments
    .enableVersioning(Encore.isProduction())
    .enableSourceMaps(!Encore.isProduction())

// export the final configuration
module.exports = Encore.getWebpackConfig()