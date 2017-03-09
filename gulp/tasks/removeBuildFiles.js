/* jshint node:true */
var config = require('../config');
if (!config.tasks.removeBuildFiles) {
    return;
}

var gulp = require('gulp');
var path = require('path');
var size = require('gulp-size');
var clean = require('gulp-clean');

var paths = {
    src: path.join(config.tasks.removeBuildFiles.src)
};

var removeBuildTask = function () {
    return gulp.src(paths.src, {read: false})
        .pipe(clean());
};

gulp.task('removeBuildFiles', removeBuildTask);
module.exports = removeBuildTask;
