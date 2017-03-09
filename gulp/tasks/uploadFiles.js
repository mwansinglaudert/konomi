/* jshint node:true */
var config = require('../config');
if (!config.tasks.uploadFiles) {
    return;
}

var gulp = require('gulp');
var path = require('path');
var size = require('gulp-size');
var GulpSSH = require('gulp-ssh')

var optfile = '../../'+config.tasks.uploadFiles.file;
var opt = require(optfile);
    opt.config.privateKey = config.tasks.uploadFiles.sshKey;

var paths = {
    src: path.join(config.tasks.uploadFiles.src,  '**/**.*'),
    dest: path.join(opt.dest)
};

var gulpSSH = new GulpSSH({
    ignoreErrors: false,
    sshConfig: opt.config
});

var uploadTask = function () {
    return gulp.src(paths.src).pipe(gulpSSH.dest(paths.dest))
};

gulp.task('uploadFiles', uploadTask);
module.exports = uploadTask;
