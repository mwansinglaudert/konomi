/* jshint node:true */
var config = require('../config');
if (!config.tasks.php) {
    return;
}

var gulp = require('gulp');
var browserSync = require('browser-sync');
var path = require('path');
var size = require('gulp-size');

var paths = {
    src: path.join(config.tasks.php.src, '/**/*.php')
};

var phpTask = function () {
    return gulp.src(paths.src)
        .pipe(browserSync.stream());
};

gulp.task('php', phpTask);
module.exports = phpTask;
