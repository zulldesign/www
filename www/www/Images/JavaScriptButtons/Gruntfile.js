'use strict';


module.exports = function (grunt) {

    require('load-grunt-config')(grunt, {
        configPath: require('path').resolve('tasks')
    });

    grunt.registerTask('lint', ['jshint', 'eslint']);
    grunt.registerTask('coverage', ['mocha_istanbul']);
    grunt.registerTask('mocha', ['mocha_istanbul']);
    grunt.registerTask('themify', ['templates', 'css', 'images', 'content']);
    grunt.registerTask('test', ['lint', 'build', 'coverage']);
    grunt.registerTask('develop', ['browserify', 'themify']);
    grunt.registerTask('build', ['browserify', 'themify', 'uglify', 'usebanner']);

};


