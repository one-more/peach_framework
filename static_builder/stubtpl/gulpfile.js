'use strict';

var gulp = require('gulp'),
    watch = require('gulp-watch'),
    prefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    cssmin = require('gulp-cssmin'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    rigger = require('gulp-rigger'),
    imagemin = require('gulp-imagemin'),
    pngquant = require('imagemin-pngquant'),
    rimraf = require('rimraf'),
    connect = require('gulp-connect'),
    opn = require('opn');

var path = {
    build: { //Тут мы укажем куда складывать готовые после сборки файлы
        js: '../www/stubtpl/js/',
        css: '../www/stubtpl/css/',
        img: '../www/stubtpl/images/'
    },
    src: { //Пути откуда брать исходники
        js: '../templates/stubtpl/js/*.js',
        style: '../templates/stubtpl/css/*.css',
        img: '../templates/stubtpl/images/**/*.*' //Синтаксис img/**/*.* означает - взять все файлы всех расширений из папки и из вложенных каталогов
    },
    watch: { //Тут мы укажем, за изменением каких файлов мы хотим наблюдать
        js: '../templates/stubtpl/js/*.js',
        style: '../templates/stubtpl/css/*.css',
        img: '../templates/stubtpl/images/**/*.*'
    },
    clean: './build'
};

gulp.task('js:build', function () {
    gulp.src(path.src.js)
        .pipe(rigger())
        .pipe(uglify())
        .pipe(gulp.dest(path.build.js));
});

gulp.task('style:build', function () {
    gulp.src(path.src.style)
        .pipe(rigger())
        .pipe(prefixer({
            browsers: ['last 4 versions']
        }))
        .pipe(cssmin())
        .pipe(gulp.dest(path.build.css));
});

gulp.task('image:build', function () {
    gulp.src(path.src.img)
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()],
            interlaced: true
        }))
        .pipe(gulp.dest(path.build.img));
});

gulp.task('watch:css', function() {
    watch([path.watch.style], function(event, cb) {
       gulp.start('style:build');
    });
});

gulp.task('watch:js', function() {
    watch([path.watch.js], function(event, cb) {
        gulp.start('js:build');
    });
});

gulp.task('watch:image', function() {
    return gulp.src(path.src.img)
        .pipe(watch(path.watch.img))
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()],
            interlaced: true
        }))
        .pipe(gulp.dest(path.build.img));
});

gulp.task('clean', function (cb) {
    rimraf(path.clean, cb);
});

gulp.task('build', ['js:build', 'style:build', 'image:build']);

gulp.task('default', ['watch:css', 'watch:js', 'watch:image']);