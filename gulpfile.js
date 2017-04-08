const autoprefixer = require('autoprefixer-stylus');
// const sourcemaps = require('gulp-sourcemaps');
const gulp = require('gulp');
const stylus = require('gulp-stylus');
const imagemin = require('gulp-imagemin');
const replace = require('gulp-replace');
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

const uglifyConfig = {
    compress: {
        dead_code: true,
        unused: true,
        drop_debugger: true,
        drop_console: true,
    },
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

    return merge(php, svg);
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


// ==================================================
//               Plugin/Theme Fixes
// ==================================================

const avada = {
    imageSizes: {
        regex: /(\s)(add_action\(.+'add_image_size'.+)/g,
        replacement: '$1//$2',
    },
    dynamicCss: {
        regex: /(^\s+)(\$this->dynamic_css\s+= new Avada_Dynamic_CSS\(\);)/gm,
        replacement: '$1//$2',
    },
    stubDynamicCssCall: {
        regex: /(public function reset_cache\(\) {)\n/gm,
        replacement: '$1 return;\n',
    },
};


gulp.task('fix-theme', () => {
    const classAvadaInit = gulp
        .src('./wp-content/themes/Avada/includes/class-avada-init.php', { base: './' })
        .pipe(replace(avada.imageSizes.regex, avada.imageSizes.replacement))
        .pipe(gulp.dest('./'));

    const dynamicCss = gulp
        .src([
            './wp-content/themes/Avada/includes/class-avada.php',
            './wp-content/themes/Avada/includes/class-avada-avadaredux.php',
        ], { base: './' })
        .pipe(replace(avada.dynamicCss.regex, avada.dynamicCss.replacement))
        .pipe(replace(avada.stubDynamicCssCall.regex, avada.stubDynamicCssCall.replacement))
        .pipe(gulp.dest('./'));

    return merge(classAvadaInit, dynamicCss);
});
