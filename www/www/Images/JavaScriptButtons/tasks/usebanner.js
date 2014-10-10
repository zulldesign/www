'use strict';


module.exports = function usebanner(grunt) {

    return {
        all: {
            options: {
                banner: grunt.file.read('.banner')
            },
            files: {
                src: [ 'dist/**/*.js' ]
            }
        }
    };

};
