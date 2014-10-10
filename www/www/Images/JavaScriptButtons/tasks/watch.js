'use strict';


module.exports = function watch(grunt) {

    return {
        scripts: {
            files: ['src/**/*'],
            tasks: ['develop'],
            options: {
                spawn: false
            }
        }
    };

};
