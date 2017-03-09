/* jshint node:true */
var config = require('../config');
if (!config.tasks.img) {
    return;
}
var gulp = require('gulp');
var size = require('gulp-size');
var flatten = require('gulp-flatten');
var path = require('path');
var nop = require('gulp-nop');

var paths = {
    src: path.join(config.root.src, '**/*.{'+config.tasks.fonts.extensions.join(',')+'}'),
    dest: path.join(config.root.dest, config.tasks.fonts.dest)
};

var fileLog = function(msg){
    if(config.log){
        return size({title: msg, showFiles: true});
    }else {
        return nop();
    }
};

var fontTask = function(){
    return gulp.src(paths.src)
        .pipe(flatten())
        .pipe(fileLog('[font] fonts move -> '))
        .pipe(gulp.dest(paths.dest));
};

gulp.task('fonts', fontTask);
module.exports = fontTask;