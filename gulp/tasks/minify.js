/* jshint node:true */
var config = require('../config');
if (!config.tasks.minify) {
    return;
}
var gulp = require('gulp');
var path = require('path');
var gulpFilter = require('gulp-filter');
var size = require('gulp-size');
var uglify = require('gulp-uglify');
var cleancss = require('gulp-cleancss');
var gulpRename = require("gulp-rename");

var paths = {
    src: path.join(config.root.dest, config.tasks.minify.src, '/**'),
    dest: path.join(config.root.dest, config.tasks.minify.dest)
};

var fileLog = function(msg){
    if(config.log){
        return size({title: msg, showFiles: true});
    }else {
        return nop();
    }
};

var minifyTask = function () {
    var conf = config.tasks.minify;

    var css = {filter: gulpFilter(conf.files.css, {restore: true}),dest : 'css'},
        js = {filter: gulpFilter(conf.files.js, {restore: true})};

    var rename = gulpRename({suffix: ".min"});

    var minify = {
        js :  uglify(),
        css: cleancss(conf.cssMinify)
    };

    return gulp.src(paths.src)
        .pipe(css.filter)
        .pipe(minify.css)
        .pipe(fileLog('[minify] minify and move css->'))
        .pipe(rename)
        .pipe(gulp.dest(paths.dest))
        .pipe(css.filter.restore)
        .pipe(js.filter)
        .pipe(fileLog('[minify] minify and move js->'))
        .pipe(minify.js)
        .pipe(rename)
        .pipe(gulp.dest(paths.dest))
        .pipe(js.filter.restore);
};

gulp.task('minify', minifyTask);
module.exports = minifyTask;
