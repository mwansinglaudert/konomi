/* jshint node:true */
var config = require('../config');
if (!config.tasks.build) {
    return;
}
var gulp = require('gulp');
var gulpSequence = require('run-sequence');

var buildTask = function (cb) {
    var tasks = config.tasks.build.sequence;

    gulpSequence(tasks.cleanTasks, tasks.assetTasks, tasks.codeTasks, tasks.minifyTasks, cb);
};

gulp.task('build', buildTask);
module.exports = buildTask;
