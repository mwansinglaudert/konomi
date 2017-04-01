/* jshint node:true */
var config = require('../config');
var gulp = require('gulp');
var path = require('path');
var watch = require('gulp-watch');

var watchTask = function () {
    var watchableTasks = ['images', 'js', 'css', 'twig'];

    watchableTasks.forEach(function (taskName) {
        var task = config.tasks[taskName];
        if (task) {
            if(taskName == 'php'){
                var glob = path.join(task.src, '**/*.{' + task.extensions.join(',') + '}');
            }
            else {
                var glob = path.join(config.root.src, task.src, '**/*.{' + task.extensions.join(',') + '}');
            }
            watch(glob, function () {
                require('./' + taskName)();
            });
        }
    });
};

gulp.task('watch', ['browserSync'], watchTask);
module.exports = watchTask;
