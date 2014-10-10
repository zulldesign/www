'use strict';


module.exports = function jshint(grunt) {

    return {
        options: grunt.file.readJSON('.jshintrc'),
        all: ['src/**/*.js', 'test/**/*.js', '!test/functional/lib/*.js']
    };

};
