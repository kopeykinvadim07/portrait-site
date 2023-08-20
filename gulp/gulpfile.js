'use strict'
const sass = require('gulp-sass')(require('sass'));
var gulp = require('gulp'),
    autoprefixer = require('gulp-autoprefixer'),
    minifyCss = require('gulp-minify-css'),
    gcmq = require('gulp-group-css-media-queries'),
    sftp = require('gulp-sftp'),
    dependents = require('gulp-dependents'),
    paths = {
        gulpDirCss: [
            '../src/css/',
        ],
        css: [
          '../src/css/*.css',
        ],
        scss: [
            '../src/scss/*.scss',
            '../src/scss/**/*.scss'
        ],
        gutenbergCss: [
            '../template-parts/blocks/'
        ],
        gutenbergScss: [
            '../template-parts/blocks/*/*.scss',
        ]
    };

function wrapPipe(taskFn) {
    return function(done) {
        var onSuccess = function() {
            done();
        };
        var onError = function(err) {
            done(err);
        }
        var outStream = taskFn(onSuccess, onError);
        if (outStream && typeof outStream.on === 'function') {
            outStream.on('end', onSuccess);
        }
    }
};

gulp.task('deploy-css', function () {
    return gulp.src('../src/css/style.css')
      .pipe(sftp({
          host: 'website.com',
          user: 'johndoe',
          pass: '1234',
          port: '22',
          remotePath: '/',
      }));
});

function compileSCSS (cb) {
    gulp.src(paths.scss[0], { since: gulp.lastRun(compileSCSS) }) // filter only changed files
        .pipe(dependents())
        .pipe(sass().on('error', sass.logError))
        .pipe(gcmq())
        .pipe(minifyCss())
        .pipe(autoprefixer('last 15 version', 'safari 5', 'ie 8', 'ie 9'))
        .pipe(gulp.dest(paths.gulpDirCss[0]))
        .on('end', cb);
}

function gutenbergSCSS(cb) {
    gulp.src(paths.gutenbergScss, { since: gulp.lastRun(gutenbergSCSS) }) // filter only changed files
        .pipe(dependents())
        .pipe(sass().on('error', sass.logError))
        .pipe(gcmq())
        .pipe(minifyCss())
        .pipe(autoprefixer('last 15 version', 'safari 5', 'ie 8', 'ie 9'))
        .pipe(gulp.dest(paths.gutenbergCss))
        .on('end', cb);
}

gulp.task('scss', function () {
    return gulp.src(paths.scss[0])
        .pipe(sass().on('error', sass.logError))
        .pipe(gcmq())
        .pipe(minifyCss())
        .pipe(autoprefixer('last 15 version', 'safari 5', 'ie 8', 'ie 9'))
        .pipe(gulp.dest(paths.gulpDirCss[0]));
});

gulp.task('deploy', function () {
    gulp.watch(paths.scss, gulp.parallel('scss'));
    gulp.watch(paths.gutenbergScss, gutenbergSCSS);
    gulp.watch(paths.css, gulp.parallel('deploy-css'));
});

gulp.task('watch', function () {
    gulp.watch(paths.scss, gulp.parallel('scss'));
    gulp.watch(paths.gutenbergScss, gutenbergSCSS);
});

gulp.task('default', gulp.parallel('watch'));
