/* jshint node:true */
var config = require('../config');
if (!config.tasks.js) {
    return;
}
var gulp = require('gulp');
var concat = require('gulp-concat');
var path = require('path');
var size = require('gulp-size');
var browserSync = require('browser-sync');
var gulpFilter = require('gulp-filter');
var uglify = require('gulp-uglify');
var nop = require('gulp-nop');

var paths = {
    src: path.join(config.root.src, config.tasks.js.src, '/**/*'),
    dest: path.join(config.root.dest, config.tasks.js.dest)
};

var fileLog = function(msg){
    if(config.log){
        return size({title: msg, showFiles: true});
    }else {
        return nop();
    }
};

var extra = config.tasks.js.extra;
var jsTask = function(){
    var concatFilter = gulpFilter(['*', '!'+extra], {restore: true, passthrough: false});
    return gulp.src(paths.src)
        .pipe(concatFilter)
        .pipe(fileLog('[js] concat to '+config.tasks.js.concat+'  -> '))
        .pipe(concat(config.tasks.js.concat))
        .pipe(fileLog('[js] move ->'))
        .pipe(gulp.dest(paths.dest))
        .pipe(browserSync.stream());
};

gulp.task('js', jsTask);
module.exports = jsTask;
