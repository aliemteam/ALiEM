const autoprefixer = require('autoprefixer-stylus');
const sourcemaps = require('gulp-sourcemaps');
const gulp = require('gulp');
const stylus = require('gulp-stylus');
const imagemin = require('gulp-imagemin');
const replace = require('gulp-replace');
const uglify = require('gulp-uglify');
const csso = require('gulp-csso');
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
    .pipe(sourcemaps.init())
    .pipe(stylus(stylusConfig))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest('dist/aliem'))
    .pipe(browserSync.stream())
));

gulp.task('stylus:prod', () => {
    const main = gulp
        .src('aliem/styles/style.styl', { base: './aliem/styles' })
        .pipe(stylus(Object.assign({}, stylusConfig, { compress: true })))
        .pipe(gulp.dest('dist/aliem'));
    const editor = gulp
        .src('aliem/styles/editor.styl', { base: './aliem/styles' })
        .pipe(stylus(Object.assign({}, stylusConfig, { compress: true })))
        .pipe(gulp.dest('dist/aliem'));
    const admin = gulp
        .src('aliem/styles/admin.styl', { base: './aliem/styles' })
        .pipe(stylus(Object.assign({}, stylusConfig, { compress: true })))
        .pipe(gulp.dest('dist/aliem'));

    return merge(main, editor, admin);
});

gulp.task('_build', gulp.series('chown', 'del', gulp.parallel('static', 'stylus:prod')));

gulp.task('_dev', gulp.series(
    'chown', 'del',
    gulp.parallel('static', 'stylus:dev'), () => {
        browserSync.init({
            proxy: 'localhost:8080',
        });

        gulp.watch('aliem/styles/**/*.styl', gulp.series('stylus:dev'));

        gulp.watch([
            'aliem/**/*.php',
        ], gulp.series('static', 'reload'));
    }));


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
            './wp-content/themes/Avada/includes/avadaredux/class-avada-avadaredux.php',
        ], { base: './' })
        .pipe(replace(avada.dynamicCss.regex, avada.dynamicCss.replacement))
        .pipe(replace(avada.stubDynamicCssCall.regex, avada.stubDynamicCssCall.replacement))
        .pipe(gulp.dest('./'));

    return merge(classAvadaInit, dynamicCss);
});

gulp.task('shrink-plugin', () => {
    if (!process.argv[3]) {
        console.log("You must specify a plugin directory name (eg. '--academic-bloggers-toolkit')"); // eslint-disable-line
        process.exit();
    }
    const plugin = process.argv[3].substring(2);

    const js = gulp
        .src(`wp-content/plugins/${plugin}/**/*.js`, { base: './' })
        .pipe(uglify(uglifyConfig))
        .pipe(gulp.dest('.'));

    const css = gulp
        .src(`wp-content/plugins/${plugin}/**/*.css`, { base: './' })
        .pipe(csso())
        .pipe(gulp.dest('.'));

    return merge(css, js);
});
