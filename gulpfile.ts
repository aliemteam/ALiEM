// tslint:disable:no-console
import { exec as cp_exec, spawn } from 'child_process';
import * as fs from 'fs';
import * as gulp from 'gulp';
import * as autoprefixer from 'gulp-autoprefixer';
import * as imagemin from 'gulp-imagemin';
import * as rename from 'gulp-rename';
import * as sass from 'gulp-sass';
import * as sourcemaps from 'gulp-sourcemaps';
import * as merge from 'merge-stream';
import { promisify } from 'util';

const VERSION = require('./package.json').version;
const IS_PRODUCTION = process.env.NODE_ENV === 'production';

const browserSync = require('browser-sync').create();
const exec = promisify(cp_exec);
const readFile = promisify(fs.readFile);
const writeFile = promisify(fs.writeFile);

process.env.FORCE_COLOR = '1';

// prettier-ignore
const reload = (cb: () => void) => { browserSync.reload(); cb(); }
const clean = () => exec(`rm -rf ${__dirname}/dist/aliem/*`);
export { clean, reload };

export const bump = () =>
    readFile(`${__dirname}/aliem/functions.php`, 'utf-8')
        .then(file => file.replace(/(define\('ALIEM_VERSION', ')(.+?)('\);)/, `$1${VERSION}$3`))
        .then(file => writeFile(`${__dirname}/aliem/functions.php`, file))
        .catch(e => {
            console.log(e);
            throw e;
        });

export function staticFiles() {
    const php = gulp
        .src(['aliem/**/*.{php,css,json,html}', '!aliem/pages/*.php'], {
            base: './',
        })
        .pipe(gulp.dest('dist'));

    const pages = gulp.src(['aliem/pages/*.php']).pipe(gulp.dest('dist/aliem'));

    const partials = gulp
        .src(['aliem/pages/partials/*.php'])
        .pipe(gulp.dest('dist/aliem/partials'));

    const svg = gulp
        .src('aliem/assets/**/*', { base: './' })
        .pipe(imagemin())
        .pipe(gulp.dest('dist'));

    const vendor = gulp
        .src([
            'aliem/vendor/**/*',
            `node_modules/react/umd/react.${IS_PRODUCTION ? 'production.min' : 'development'}.js`,
            // prettier-ignore
            `node_modules/react-dom/umd/react-dom.${IS_PRODUCTION ? 'production.min' : 'development'}.js`,
        ])
        .pipe(
            rename(path => {
                if (!path.basename) return;
                path.basename = path.basename.split('.')[0];
            }),
        )
        .pipe(gulp.dest('dist/aliem/vendor'));

    return merge(php, pages, svg, partials, vendor);
}

export function styles() {
    let stream = gulp.src('aliem/styles/[^_]*.scss', { base: './aliem/styles' });

    if (!IS_PRODUCTION) {
        stream = stream.pipe(sourcemaps.init());
    }

    stream = stream
        .pipe(
            sass({
                outputStyle: IS_PRODUCTION ? 'compressed' : 'nested',
            }).on('error', sass.logError),
        )
        .pipe(autoprefixer({ browsers: ['last 2 versions'] }));

    if (!IS_PRODUCTION) {
        stream = stream.pipe(sourcemaps.write('.'));
    }

    stream = stream.pipe(gulp.dest('dist/aliem'));

    if (!IS_PRODUCTION) {
        stream = stream.pipe(browserSync.stream({ match: '**/*.css' }));
    }

    return stream;
}

export function bundle(cb: () => void) {
    const child = spawn(`${__dirname}/node_modules/.bin/webpack`, undefined, {
        env: process.env,
    });
    child.on('error', err => {
        console.error(err);
        process.exit(1);
    });
    child.on('exit', (code, signal) => {
        if (code !== 0) {
            console.error(`Exited with non-zero exit code (${code}): ${signal}`);
            process.exit(1);
        }
        cb();
    });
    child.on('disconnect', () => child.kill());
    child.stdout.on('data', data => {
        const msg = data.toString();
        console.log(msg.trim());
        if (msg.indexOf('[at-loader] Ok') > -1) {
            browserSync.reload();
        }
    });
    child.stderr.on('data', data => {
        const msg = data.toString();
        console.error(msg.trim());
    });
    if (!IS_PRODUCTION) return cb();
}

const main = gulp.series(clean, gulp.parallel(styles, staticFiles), bundle, (cb: () => void) => {
    if (IS_PRODUCTION) return cb();
    gulp.watch('aliem/styles/**/*.scss', gulp.series(styles));
    gulp.watch(['aliem/**/*.{php,json,html}'], gulp.series(staticFiles, reload));

    browserSync.init({
        proxy: 'localhost:8080',
        open: false,
        notify: false,
        reloadDebounce: 2000,
    });
});

export default main;
