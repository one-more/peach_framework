var lazypipe = require('lazypipe'),
    babel = require('gulp-babel'),
    stylus = require('gulp-stylus'),
    nib = require('nib'),
    cssnext = require('gulp-cssnext'),
    prefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    cssmin = require('gulp-cssmin');

var default_options = {
    useMin: true,
    uglify: false,
    minCSS: false,
    transforms: {
        scripts: lazypipe().pipe(babel).pipe(uglify),
        styles: lazypipe().pipe(stylus).pipe(prefixer).pipe(cssmin).pipe(cssnext)
    },
    pluginOptions: {
        "gulp-stylus": {use: nib()},
        "gulp-cssnext": {compress: true}
    },
    watch: {
        scripts: true,
        styles: true
    }
};

var vendor_js = [
    './src/vendor/jquery-2.1.3.js',
    './src/vendor/underscore.js',
    './src/vendor/backbone.js',
    './src/vendor/bootstrap/bootstrap.js',
    './src/vendor/crypto/md5.js',
    './src/vendor/smart.core.min.js'
];

var vendor_css = [
    './src/vendor/bootstrap/bootstrap.css',
    './src/vendor/bootstrap/bootstrap-theme.css',
    './src/vendor/icomoon.css'
];

module.exports = {
    bundle: {
        vendor: {
            scripts: vendor_js,
            styles: vendor_css,
            options: {
                useMin: true,
                uglify: false,
                minCSS: false,
                transforms: {
                    scripts: lazypipe().pipe(uglify),
                    styles: lazypipe().pipe(cssmin)
                },
                watch: {
                    scripts: false,
                    styles: false
                }
            }
        },
        starter: {
            scripts: [
                './src/global/js/**/*.js',
                './src/starter/js/**/*.js'
            ],
            styles: [
                './src/starter/styles/**/*.styl',
                './src/global/styles/**/*.styl'
            ],
            options: default_options
        },
        "admin_panel": {
            scripts: [
                './src/vendor/notification/modernizr-custom-3.1.0.js',
                './src/vendor/notification/classie.js',
                './src/vendor/notification/notificationFx.js',
                './src/global/js/**/*.js',
                './src/admin_panel/js/**/*.js'
            ],
            styles: [
                './src/admin_panel/styles/**/*.styl',
                './src/admin_panel/styles/**/*.css',
                './src/global/styles/**/*.styl'
            ],
            options: default_options
        },
        tools: {
            scripts: [
                './src/global/js/**/*.js',
                './src/tools/js/**/*.js'
            ],
            styles: [
                './src/tools/styles/**/*.styl',
                './src/global/styles/**/*.styl'
            ],
            options: default_options
        }
    }
};
