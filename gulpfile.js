/*eslint-env es6*/
'use strict';

const gulp = require('gulp');
const replace = require('gulp-replace');
const merge = require('merge-stream');

// CSS
const stylus = require('gulp-stylus');
const poststylus = require('poststylus');
const autoprefixer = require('autoprefixer')({ browsers: ['last 2 versions'] });
const sourcemaps = require('gulp-sourcemaps');
const insert = require('gulp-insert');


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
//                     Static
// ==================================================
gulp.task('static', () =>
    gulp.src('aliem/**/*.php', { base: './' })
    .pipe(gulp.dest('dist'))
);

// ==================================================
//                     Styles
// ==================================================

gulp.task('stylus:dev', () =>
    gulp
       .src('aliem/styles/style.styl', { base: './aliem/styles' })
       .pipe(sourcemaps.init())
       .pipe(stylus(stylusConfig))
       .pipe(sourcemaps.write('.'))
       .pipe(insert.prepend(styleHeader))
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



gulp.task('build', gulp.parallel('static', 'stylus:prod'));


// ==================================================
//               Plugin/Theme Fixes
// ==================================================

const avada = {
    addEditors: {
        regex: /\/\/ Render the HTML wrappers for the [\s\S]+\$metadata;$/m,
        replacement: `
        $editors = '';
        if ( get_post_meta(get_the_id(), 'editors', true) ) {
    		$editors = sprintf('<span class="fusion-inline-sep">|</span><span>Editors: %s</span>', get_post_meta(get_the_id(), 'editors', true));
    	}
        // FIXED Render the HTML wrappers for the different layouts
        if ( $metadata ) {
            $metadata = $author . $date . $metadata . $editors;
        `,
    },
    imageSizes: {
        regex: /add_action\( 'after_setup_theme', array\( \$this, 'add_image_size' \) \);/,
        replacement: '',
    }
}


gulp.task('fix-theme', () => {

    const avadaFunctions = gulp
        .src('./wp-content/themes/Avada/includes/avada-functions.php', { base: './' })
        .pipe(replace(avada.addEditors.regex, avada.addEditors.replacement))
        .pipe(gulp.dest('./'));

    const classAvadaInit = gulp
        .src('./wp-content/themes/Avada/includes/class-avada-init.php', { base: './' })
        .pipe(replace(avada.imageSizes.regex, avada.imageSizes.replacement))
        .pipe(gulp.dest('./'));

    return merge(avadaFunctions, classAvadaInit);

});
