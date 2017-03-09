/* jshint node:true */
var config = require('../config');
if (!config.tasks.css) {
    return;
}

var gulp = require('gulp');
var path = require('path');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var browserSync = require('browser-sync');
var handleErrors = require('../utils/handleErrors');
var size = require('gulp-size');
var nop = require('gulp-nop');

var paths = {
    src: path.join(config.root.src, config.tasks.css.src, '/*.{' + config.tasks.css.extensions + '}'),
    dest: path.join(config.root.dest, config.tasks.css.dest)
};

var fileLog = function(msg){
    if(config.log){
        return size({title: msg, showFiles: true});
    }else {
        return nop();
    }
};

var cssTask = function () {
    return gulp.src(paths.src)
        .pipe(sourcemaps.init())
        .pipe(sass(config.tasks.css.sass))
        .on('error', handleErrors)
        .pipe(fileLog('[css] compiled ->'))
        .pipe(autoprefixer(config.tasks.css.autoprefixer))
        .pipe(fileLog('[css] autoprefixed ->'))
        .pipe(sourcemaps.write('.', {includeContent: false}))
        .pipe(gulp.dest(paths.dest))
        .pipe(browserSync.stream());
};

gulp.task('css', cssTask);
module.exports = cssTask;
