module.exports = function(grunt) {
    
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        watch: {
            scripts: {
                files: ['src/*', 'tests/*'],
                tasks: ['phpunit']
            }
        },
        phpunit: {
            classes: {
                dir: 'tests/'
            },
            options: {
                bin: 'vendor/bin/phpunit',
                configuration: 'phpunit.xml',
                colors: true
            }
        }
    });
    
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-phpunit');
    grunt.registerTask('default', ['phpunit']);
};