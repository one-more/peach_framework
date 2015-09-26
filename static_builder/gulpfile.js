'use strict';

var gulp = require('gulp'),
    bundle = require('gulp-bundle-assets'),
    rimraf = require('gulp-rimraf'),
    path = require('path');

var paths = {
    build: '../www/static',
    prefix: '/static/',
    delete_cwd: '../www/',
    delete_path: './static/'
};

gulp.task('bundle', ['clean'], function () {
    return gulp.src('./bundle.config.js')
    .pipe(bundle())
        .on('error', console.log)
    .pipe(bundle.results({
            dest: './',
            pathPrefix: paths.prefix
        }))
    .pipe(gulp.dest(paths.build))
});

gulp.task('clean', function () {
    return gulp.src(paths.delete_path, {read: false, cwd: paths.delete_cwd})
            .pipe(rimraf())
});

gulp.task('watch', function () {
    bundle.watch({
        configPath: path.join(__dirname, 'bundle.config.js'),
        results: {
            dest: __dirname,
            pathPrefix: paths.prefix
        },
        dest: path.join(__dirname, paths.build)
    })
});

gulp.task('default', ['watch']);