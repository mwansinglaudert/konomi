/* jshint node:true */
var path = require('path');
var fs = require('fs');
var os = require('os');

var SRC_PATH = './src/assets';
var DEST_PATH = './dist/assets';

module.exports = {
    log: true,
    root: {
        src: SRC_PATH,
        dest: DEST_PATH
    },
    tasks: {
        clean: {
            src: '*'
        },
        build: {
            sequence: {
                cleanTasks: ['clean'],
                assetTasks: ['img', 'fonts'],
                codeTasks: ['css', 'js', 'libs'],
                minifyTasks: ['minify']
            }
        },
        watch: {
            tasks: ['img', 'js', 'css']
        },
        browserSync: {
            proxy: 'dev.app.konomi',
            port: 9000,
            notify: false
        },
        css: {
            src: 'scss',
            dest: 'css',
            extensions: ['scss', 'css'],
            autoprefixer: {
                browsers: ['last 2 version']
            }
        },
        js: {
            src: 'js/scripts',
            dest: 'js',
            concat: 'scripts.js',
            extensions: ['js'],
            uglify: {}
        },
        libs: {
            src: 'js/libs',
            dest: 'js',
            concat: 'libs.js',
            extensions: ['js'],
            uglify: {}
        },
        minify: {
            src: '',
            dest: '',
            cssMinify: {
                compatibility: 'ie8',
                keepBreaks: false,
                processImport: false
            },
            files: {
                css: [
                    '**/style.css'
                ],
                js: [
                    '**/scripts.js',
                    '**/libs.js'
                ]
            }
        },
        php: {
            src: './',
            extensions: ['php']
        },
        img: {
            src: 'img',
            dest: 'img',
            extensions: ['jpg', 'png', 'svg', 'gif', 'ico'],
            clean: true
        },
        fonts: {
            src: 'fonts',
            dest: 'fonts',
            extensions: ['woff', 'woff2', 'ttf', 'svg', 'eot']
        },
        fonts: {
            src: 'fonts',
            dest: 'fonts',
            extensions: ['woff', 'woff2', 'ttf', 'eot', 'svg'],
            clean: true
        },
        createBuildFiles: {
            src: './',
            dest: './build',
            files: [
                'dist/**/**.*',
                'functions/**/**.*',
                'templates/**/**.*',
                'index.php']
        },
        removeBuildFiles: {
            src: './build'
        },
        uploadFiles: {
            file: 'deploy.js',
            src: './build',
            sshKey: fs.readFileSync(os.homedir()+'/.ssh/id_rsa')
        }
    }
};
