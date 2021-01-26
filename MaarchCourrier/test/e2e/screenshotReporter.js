var fs = require('fs');

var ScreenshotReporter = function writeScreenShot(data, filename, mode = 'base64') {
    if (mode === 'base64') {
        var stream = fs.createWriteStream(filename+'.png');
        stream.write(new Buffer.from(data, 'base64'));
        stream.end();
    } else if (mode === 'txt') {
        var stream = fs.createWriteStream(filename+'.txt');
        stream.write(data);
        stream.end();
    } else if (mode === 'console') {
        console.log(data);
    }
}

module.exports = ScreenshotReporter;