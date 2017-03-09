var config = require('../config');
if (!config.tasks.libs) {
    return;
}

var gulp = require('gulp');
var concat = require('gulp-concat');
var size = require('gulp-size');
var path = require('path');
var uglify = require('gulp-uglify');
var nop = require('gulp-nop');

var paths = {
    src: path.join(config.root.src, config.tasks.libs.src, '/**/*'),
    dest: path.join(config.root.dest, config.tasks.libs.dest)
};

var fileLog = function(msg){
    if(config.log){
        return size({title: msg, showFiles: true});
    }else {
        return nop();
    }
};

var libsTask = function () {
    return gulp.src(paths.src)
        .pipe(fileLog('[libs] concat to '+config.tasks.libs.concat+'  -> '))
        .pipe(concat(config.tasks.libs.concat))
        .pipe(fileLog('[libs] move ->'))
        .pipe(gulp.dest(paths.dest));
};

gulp.task('libs', libsTask);
module.exports = libsTask;
