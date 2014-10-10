'use strict';


module.exports = function browserify(grunt) {

    return {
        all: {
            files: {
                'dist/button.js': ['src/**/*.js']
            }
        }
    };

};
