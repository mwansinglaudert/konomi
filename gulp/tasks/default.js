/* jshint node:true */
var gulp = require('gulp');
var gulpSequence = require('run-sequence');

var defaultTask = function (cb) {
    gulpSequence('build', 'watch',cb);
};

gulp.task('default', defaultTask);
module.exports = defaultTask;
