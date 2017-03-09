/* jshint node:true */
var gulp = require('gulp');
var gulpSequence = require('run-sequence');

var deployTask = function (cb) {
    gulpSequence('build', 'createBuildFiles','uploadFiles','removeBuildFiles',cb);
};

gulp.task('deploy', deployTask);
module.exports = deployTask;
