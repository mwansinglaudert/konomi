/* jshint node:true */
var config = require('../config');
if (!config.tasks.img) {
    return;
}
var gulp = require('gulp');
var size = require('gulp-size');
var path = require('path');
var gulpFilter = require('gulp-filter');
var changed = require('gulp-changed');
var imagemin = require('gulp-imagemin');
var nop = require('gulp-nop');

var paths = {
    src: path.join(config.root.src, '**/*.{'+config.tasks.img.extensions.join(',')+'}'),
    dest: path.join(config.root.dest)
};

var fileLog = function(msg){
    if(config.log){
        return size({title: msg, showFiles: true});
    }else {
        return nop();
    }
};

var imgTask = function(){
    var imgFilter = gulpFilter([config.tasks.img.src+'/**/*'], {restore: true});

    return gulp.src(paths.src)
        .pipe(imgFilter)
        .pipe(changed(paths.dest))
        .pipe(imagemin({progressive: true}))
        .pipe(fileLog('[img] ./img min/move -> '))
        .pipe(gulp.dest(paths.dest));
};

gulp.task('img', imgTask);
module.exports = imgTask;