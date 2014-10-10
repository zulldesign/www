'use strict';


function base64(str) {
    return 'data:image/png;base64,' + str.toString('base64');
}


module.exports = function images(grunt) {

    var src = 'dist/button.js';

    function processImages(str) {
        var files = grunt.file.expand('src/theme/images/*.*'),
            templates = {},
            name,
            token,
            contents;

        files.forEach(function (file) {
            name = file.split(/src\/theme\/images\/(.*)\.(.*)/);
            token = '$' + name[1].toUpperCase() + '$';
            contents = grunt.file.read(file, { encoding: null });

            if (contents) {
                str = str.replace(token, base64(contents));
            } else {
                grunt.fail.warn('Looks like ' + file + ' is missing. Check that it exists.');
            }
        });

        return str;
    }

    grunt.registerTask('images', 'Base64 encodes images and injects them into the JavaScript', function () {
        var out = grunt.file.read(src);

        out = processImages(out);

        grunt.file.write(src, out);
    });

};
