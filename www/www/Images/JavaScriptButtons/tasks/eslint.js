'use strict';


module.exports = function eslint(grunt) {


    return {
        options: {
            config: 'eslint.json'
        },
        src: ['src/**/*.js']
    };


};
