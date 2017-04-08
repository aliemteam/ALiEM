const autoprefixer = require('autoprefixer-stylus');
// const sourcemaps = require('gulp-sourcemaps');
const gulp = require('gulp');
const stylus = require('gulp-stylus');
const imagemin = require('gulp-imagemin');
// const replace = require('gulp-replace');
const del = require('del');
const { exec } = require('child_process');
const merge = require('merge-stream');
const browserSync = require('browser-sync').create();

// ==================================================
//                Configurations
// ==================================================

const stylusConfig = {
    use: [
        autoprefixer({ browsers: 'last 2 versions' }),
    ],
};

// ==================================================
//                     Utility
// ==================================================
gulp.task('del', done => (
    del(['dist/aliem/**', '!dist/aliem']).then(() => done())
));

gulp.task('reload', (done) => { browserSync.reload(); done(); });

gulp.task('chown', (done) => {
    exec("ls -l dist/ | awk '{print $3}' | tail -n -1", (err, stdout) => {
        if (stdout.trim() === process.env.USER) {
            done();
            return;
        }
        exec(`sudo chown -R ${process.env.USER} ${process.env.PWD}`, () => {
            done();
        });
    });
});

// ==================================================
//                     Static
// ==================================================
gulp.task('static', () => {
    const php = gulp
        .src([
            'aliem/**/*.php',
            'aliem/**/*.css',
        ], { base: './' })
        .pipe(gulp.dest('dist'));

    const svg = gulp
        .src('aliem/assets/**/*', { base: './' })
        .pipe(imagemin())
        .pipe(gulp.dest('dist'));

    const vendor = gulp
        .src('aliem/vendor/**/*', { base: 'aliem' })
        .pipe(gulp.dest('dist/aliem'));

    return merge(php, svg, vendor);
});

// ==================================================
//                     Styles
// ==================================================

gulp.task('stylus:dev', () => (
    gulp.src([
        'aliem/styles/style.styl',
        'aliem/styles/editor.styl',
        'aliem/styles/admin.styl',
    ], { base: './aliem/styles' })
    .pipe(stylus(stylusConfig))
    .pipe(gulp.dest('dist/aliem'))
    .pipe(browserSync.stream())
));

gulp.task('stylus:prod', () => (
    gulp
        .src([
            'aliem/styles/style.styl',
            'aliem/styles/editor.styl',
            'aliem/styles/admin.styl',
        ], { base: './aliem/styles' })
        .pipe(stylus(Object.assign({}, stylusConfig, { compress: true })))
        .pipe(gulp.dest('dist/aliem'))
));

gulp.task('_build', gulp.series('chown', 'del', gulp.parallel('static', 'stylus:prod')));

gulp.task('_dev', gulp.series('chown', 'del', gulp.parallel('static', 'stylus:dev'), dev));

function dev() {
    browserSync.init({
        proxy: 'localhost:8080',
    });

    gulp.watch('aliem/styles/**/*.styl', { queue: false }, gulp.series('stylus:dev'));

    gulp.watch([
        'aliem/**/*.php',
    ], gulp.series('static', 'reload'));
}
