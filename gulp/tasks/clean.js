/* jshint node:true */
var config = require('../config');
if (!config.tasks.clean) {
    return;
}
var gulp = require('gulp');
var path = require('path');
var clean = require('gulp-clean');

var paths = {
    src: path.join(config.root.dest, config.tasks.clean.src)
};

var cleanTask = function () {
    return gulp.src(paths.src, {read: false})
        .pipe(clean());
};

gulp.task('clean', cleanTask);
module.exports = cleanTask;
