/* jshint node:true */
var config = require('../config');
if (!config.tasks.twig) {
    return;
}

var gulp = require('gulp'),
    browserSync = require('browser-sync'),
    path = require('path'),
    size = require('gulp-size');

var paths = {
    src: path.join(config.tasks.twig.src, '**/*.{'+config.tasks.twig.extensions.join(',')+'}')
};

var twigTask = function () {
    return gulp.src(paths.src)
        .pipe(browserSync.stream());
};

gulp.task('twig', twigTask);
module.exports = twigTask;
