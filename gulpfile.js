/*eslint-env es6*/
'use strict';

const gulp = require('gulp');
const replace = require('gulp-replace');
const merge = require('merge-stream');


gulp.task('fix-theme', () => {

    const editorsRegex = /\/\/ Render the HTML wrappers for the [\s\S]+\$metadata;$/m;
    const editorsReplacement = `
    if ( get_post_meta(get_the_id(), 'editors', true) ) {
		$editors = sprintf('<span class="fusion-inline-sep">|</span><span>Editors: %s</span>', get_post_meta(get_the_id(), 'editors', true));
	}
    // FIXED Render the HTML wrappers for the different layouts
    if ( $metadata ) {
        $metadata = $author . $date . $metadata . $editors;
    `;

    const avadaFunctions = gulp.src(
        './wp-content/themes/Avada/includes/avada-functions.php', { base: './' }
    )
    .pipe(replace(editorsRegex, editorsReplacement))
    .pipe(gulp.dest('./'));

    return merge(avadaFunctions);

});
