/* jshint node:true */
var config = require('../config');
if (!config.tasks.createBuildFiles) {
    return;
}

var gulp = require('gulp');
var path = require('path');
var size = require('gulp-size');

var paths = {
    src: path.join(config.tasks.createBuildFiles.src,  '{' + config.tasks.createBuildFiles.files + '}'),
    dest: path.join(config.tasks.createBuildFiles.dest),
};

var createBuildTask = function () {
    return gulp.src(paths.src)
        .pipe(size({title: 'Move to Build', showFiles: true}))
        .pipe(gulp.dest(paths.dest));
};

gulp.task('createBuildFiles', createBuildTask);
module.exports = createBuildTask;
