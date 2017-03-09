/* jshint node:true */
var notify = require('gulp-notify');

module.exports = function (errorObject, callback) {
    var args = Array.prototype.slice.call(arguments);
    var trimRe = new RegExp('\u001B\\[[;\\d]*m', 'g');

    // Send error to notification center with gulp-notify
    var msg = errorObject.toString().replace(trimRe, '');
    var title = msg.split('\n')[0];
    notify.onError({
        title: title,
        message: msg.replace(title, '').split(': ').join(':\n')
    }).apply(this, args);

    // Keep gulp from hanging on this task
    if (typeof this.emit === 'function') {
        this.emit('end');
    }
};
