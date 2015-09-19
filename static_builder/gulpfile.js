'use strict';

var fs = require('fs');

var get_dirs_list = function(path) {
    var result = [];
    var files = fs.readdirSync(path);
    for(var i in files) {
        if (!files.hasOwnProperty(i)) continue;
        var name = path + '/' + files[i];
        if (fs.statSync(name).isDirectory()) {
            result.push(files[i])
        }
    }
    return result;
};

var gulp = require('gulp'),
    watch = require('gulp-watch'),
    prefixer = require('gulp-autoprefixer'),
    uglify = require('gulp-uglify'),
    cssmin = require('gulp-cssmin'),
    stylus = require('gulp-stylus'),
    nib = require('nib'),
    sourcemaps = require('gulp-sourcemaps'),
    rigger = require('gulp-rigger'),
    imagemin = require('gulp-imagemin'),
    pngquant = require('imagemin-pngquant'),
    babel = require('gulp-babel'),
    cssnext = require('gulp-cssnext');

var path = {
    build: { //Тут мы укажем куда складывать готовые после сборки файлы
        js: '../www/',
        css: '../www/css',
        img: '../www/'
    },
    src: { //Пути откуда брать исходники
        js: 'src/*/js/*.js',
        style: 'src/*/style/*.styl',
        img: 'src/*/images/**/*.*' //Синтаксис img/**/*.* означает - взять все файлы всех расширений из папки и из вложенных каталогов
    },
    watch: { //Тут мы укажем, за изменением каких файлов мы хотим наблюдать
        js: 'src/*/js/**/*.js',
        style: 'src/*/style/**/*.styl',
        img: 'src/*/images/**/*.*'
    }
};

gulp.task('js:build', function () {
    get_dirs_list('src').forEach(function(dir) {
        gulp.src('src/'+dir+'/js/*.js')
            .pipe(rigger())
            .pipe(babel())
            .on('error', console.log)
            .pipe(uglify())
            .pipe(gulp.dest('../www/'+dir+'/js'));
    })
});

gulp.task('style:build', function () {
    get_dirs_list('src').forEach(function(dir) {
        gulp.src('src/'+dir+'/style/*.styl')
            .pipe(stylus({
                use: nib()
            }))
            .on('error', console.log)
            .pipe(rigger())
            .on('error', console.log)
            .pipe(prefixer())
            .pipe(cssnext({
                compress: true
            }))
            .pipe(cssmin())
            .pipe(gulp.dest('../www/'+dir+'/css'));
    });
});

gulp.task('image:build', function () {
    get_dirs_list('src').forEach(function(dir) {
        gulp.src('src/'+dir+'/images/**/*.*')
            .pipe(imagemin({
                progressive: true,
                svgoPlugins: [{removeViewBox: false}],
                use: [pngquant()],
                interlaced: true
            }))
            .pipe(gulp.dest('../www/'+dir+'/images'));
    });
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
    get_dirs_list('src').forEach(function(dir) {
        gulp.src('src/'+dir+'/images/**/*.*')
            .pipe(watch('src/'+dir+'/images/**/*.*'))
            .pipe(imagemin({
                progressive: true,
                svgoPlugins: [{removeViewBox: false}],
                use: [pngquant()],
                interlaced: true
            }))
            .pipe(gulp.dest('../www/'+dir+'/images'));
    });
});

gulp.task('build', ['js:build', 'style:build', 'image:build']);

gulp.task('default', ['watch:css', 'watch:js', 'watch:image']);