/*eslint-env es6*/
'use strict';

const gulp = require('gulp');
const replace = require('gulp-replace');
const merge = require('merge-stream');
const del = require('del');
const browserSync = require('browser-sync').create();

// SVG
const svgmin = require('gulp-svgmin');

// CSS
const stylus = require('gulp-stylus');
const poststylus = require('poststylus');
const autoprefixer = require('autoprefixer')({ browsers: ['last 2 versions'] });
const sourcemaps = require('gulp-sourcemaps');
const insert = require('gulp-insert');
const csso = require('gulp-csso');

// JS
const uglify = require('gulp-uglify');

// ==================================================
//                Configurations
// ==================================================

const jadeConfig = {
    omitPhpRuntime: true,
    omitPhpExtractor: true,
    arraysOnly: false,
    noArraysOnly: true,
};

const stylusConfig = {
    use: [
        poststylus([
            autoprefixer,
        ]),
    ],
};

const uglifyConfig  = {
    compress: {
        'dead_code': true,
        'unused': true,
        'drop_debugger': true,
        'drop_console': true,
    },
};

const styleHeader =
`/*
Theme Name: ALiEM
Template: Avada
*/
`

// ==================================================
//                     Utility
// ==================================================
gulp.task('del', (done) =>
    del(['dist/aliem/**', '!dist/aliem']).then(() => done())
);

gulp.task('reload', (done) => { browserSync.reload(); done(); });

// ==================================================
//                     Static
// ==================================================
gulp.task('static', () => {
    const php = gulp
        .src('aliem/**/*.php', { base: './' })
        .pipe(gulp.dest('dist'));

    const svg = gulp
        .src('aliem/assets/*.svg', { base: './'} )
        .pipe(svgmin())
        .pipe(gulp.dest('dist'));

    return merge(php, svg);
});

// ==================================================
//                     Styles
// ==================================================

gulp.task('stylus:dev', () =>
    gulp
        .src('aliem/styles/style.styl', { base: './aliem/styles' })
        .pipe(sourcemaps.init())
        .pipe(stylus(stylusConfig))
        .pipe(insert.prepend(styleHeader))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('dist/aliem'))
        .pipe(browserSync.stream({ match: '**/*.css' }))
);

gulp.task('stylus:prod', () =>
    gulp
        .src('aliem/styles/style.styl', { base: './aliem/styles' })
        .pipe(stylus(Object.assign({}, stylusConfig, { compress: true })))
        .pipe(insert.prepend(styleHeader))
        .pipe(gulp.dest('dist/aliem'))
);



gulp.task('build', gulp.series('del', gulp.parallel('static', 'stylus:prod')));

gulp.task('default', gulp.series(
    'del',
    gulp.parallel('static', 'stylus:dev'), () => {

            browserSync.init({
                proxy: 'localhost:8080'
            });

            gulp.watch('aliem/**/*.styl', gulp.series('stylus:dev'));

            gulp.watch([
                'aliem/**/*',
                '!aliem/**/*.styl',
            ], gulp.series('static', 'reload'));
    }
));


// ==================================================
//               Plugin/Theme Fixes
// ==================================================

const avada = {
    imageSizes: {
        regex: /add_action\( 'after_setup_theme', array\( \$this, 'add_image_size' \) \);/,
        replacement: '',
    },
    inlineCss: {
        regex: /(^.+add_action\( 'wp_enqueue_scripts'[\s\S]+999 \);)/gm,
        replacement: '/*\n$1\n*/',
    },
};


gulp.task('fix-theme', () => {

    const classAvadaInit = gulp
        .src('./wp-content/themes/Avada/includes/class-avada-init.php', { base: './' })
        .pipe(replace(avada.imageSizes.regex, avada.imageSizes.replacement))
        .pipe(gulp.dest('./'));

    const classAvadaDynamicCss = gulp
        .src('./wp-content/themes/Avada/includes/class-avada-dynamic-css.php', { base: './' })
        .pipe(replace(avada.inlineCss.regex, avada.inlineCss.replacement))
        .pipe(gulp.dest('./'));

    return merge(avadaFunctions, classAvadaInit);

});

gulp.task('shrink-plugin', () => {
    if (! process.argv[3]) {
        console.log("You must specify a plugin directory name (eg. '--academic-bloggers-toolkit')");
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
