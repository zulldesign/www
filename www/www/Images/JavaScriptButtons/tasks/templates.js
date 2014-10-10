'use strict';


function trim(str) {
    return str.replace(/(^\s+|\s+$)/g, '').replace(/(\r\n|\n|\r)/g, '');
}


module.exports = function templates(grunt) {

    var src = 'dist/button.js';

    function processTemplates(str) {
        var files = grunt.file.expand('src/theme/**/*.html'),
            templates = {},
            name;

        files.forEach(function (file) {
            name = file.split(/src\/theme\/(.*).html/);
            name = name[1];

            templates[name] = trim(grunt.file.read(file));
        });

        return str.replace('\'$TEMPLATES$\'', JSON.stringify(templates));
    }

    grunt.registerTask('templates', 'Injects templates into the JavaScript', function () {
        var out = grunt.file.read(src);

        out = processTemplates(out);

        grunt.file.write(src, out);
    });

};
