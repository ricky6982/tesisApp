module.exports = function (grunt){
    
    var files = require('./files').files;

    // Configuración del Proyecto
    grunt.initConfig({
        ngDependencies: files.dependencies,
        builddir: 'web/public',
        pkg: grunt.file.readJSON('package.json'),
        meta: {
            banner: '/**\n'+
                ' * <%= pkg.description %>\n' +
                ' * @version v<%= pkg.version %>\n' +
                ' * @link <%= pkg.homepage %>\n' +
                ' * @license MIT License, http://www.opensource.org/licenses/MIT\n' +
                ' */'
        },

        clean: [ '<%= builddir %>' ],

        concat: {
            options: {
                banner: '(function(window, angular){\n\n',
                footer: '\n\n/**\n'+
                    ' * Definición del Modulo Principal\n'+
                    ' */\n'+
                    'angular.module(\'sueldo\', [<%= ngDependencies %>]);'+
                    '\n})(window, angular);'
            },
            build: {
                src: files.src,
                dest: '<%= builddir %>/<%= pkg.name %>.js'
            }
        },

        uglify: {
            options: {
                banner: '<%= meta.banner %>\n'
            },
            build: {
                files: {
                  '<%= builddir %>/<%= pkg.name %>.min.js': ['<banner:meta.banner>', '<%= concat.build.dest %>']
                }
            }
        },

        html2js: {
            options: {
                module: 'app.templates',
                rename: function(moduleName){
                    return moduleName.replace('../webApp/', '');
                }
            },
            main: {
                src: ['webApp/template/**/*.tpl.html'],
                dest: 'webApp/js/template.js'
            }
        },

        jshint: {
            beforeconcat: ["webApp/js/**/*.js", "webApp/test/*.js"],
            options: {
              eqnull: true
            }
        },

        sass: {
            dist: {
                options: {
                    style: 'compressed'
                },
                files: {
                    '<%= builddir %>/css/<%= pkg.name %>Backend-<%= pkg.version %>.css': 'webApp/sass/backend.scss',
                }
            }
        },

        watch: {
            startup: {
                files: [],
                tasks: ['karma:continuous:start'],
                options: {
                    atBegin: true,
                    spawn: false
                }
            },
            sass: {
                files: ['webApp/sass/*.scss'],
                tasks: ['sass']
            },
            html: {
                files: ['webApp/template/*.tpl.html'],
                tasks: ['html2js', 'concat', 'jshint']
            },
            js: {
                files: ['webApp/js/**/*.js'],
                tasks: ['concat', 'uglify', 'jshint'],
            }
        },

        karma: {
            options: {
                configFile: 'karma.conf.js',
            },
            unit: {
                singleRun: true
            },
            continuous: {
                background: true
            }
        }

    });

    grunt.registerTask('default', 'Perform a normal build', ['jshint', 'concat', 'uglify', 'karma:unit:run']);

    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-html2js');
    grunt.loadNpmTasks('grunt-karma');
};