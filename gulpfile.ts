import * as autoprefixer from 'autoprefixer-stylus';
import * as gulp from 'gulp';
import * as stylus from 'gulp-stylus';
import * as imagemin from 'gulp-imagemin';
import * as del from 'del';
import { exec as cp_exec } from 'child_process';
import * as fs from 'fs';
import * as path from 'path';
import * as merge from 'merge-stream';
import { promisify } from 'util';

const browserSync = require('browser-sync').create();
const VERSION = require('./package.json').version;

const exec = promisify(cp_exec);
const readFile = promisify(fs.readFile);
const writeFile = promisify(fs.writeFile);

gulp.task('del', () => del(['dist/aliem/**', '!dist/aliem']));

gulp.task('reload', done => {
    browserSync.reload();
    done();
});

gulp.task('bump', () =>
    readFile(`${__dirname}/aliem/functions.php`, 'utf-8')
    .then(file => file.replace(/(define\('ALIEM_VERSION', ')(.+?)('\);)/, `$1${VERSION}$3'`))
    .then(file => writeFile(`${__dirname}/aliem/functions.php`, file))
    .catch(e => {
        console.log(e);
        throw e;
    })
);

gulp.task('static', () => {
    const php = gulp
        .src(['aliem/**/*.php', 'aliem/**/*.css', '!aliem/pages/*.php'], {
            base: './',
        })
        .pipe(gulp.dest('dist'));

    const pages = gulp.src(['aliem/pages/*.php']).pipe(gulp.dest('dist/aliem'));

    const svg = gulp
        .src('aliem/assets/**/*', { base: './' })
        .pipe(imagemin())
        .pipe(gulp.dest('dist'));

    const vendor = gulp
        .src('aliem/vendor/**/*', { base: 'aliem' })
        .pipe(gulp.dest('dist/aliem'));

    return merge(php, pages, svg, vendor);
});

gulp.task('stylus', cb => {
    const stylusConfig = {
        use: [autoprefixer({ browsers: 'last 2 versions' })],
        compress: process.env.NODE_ENV === 'production',
    };

    let styles = gulp
        .src(
            [
                'aliem/styles/style.styl',
                'aliem/styles/editor.styl',
                'aliem/styles/admin.styl',
            ],
            { base: './aliem/styles' }
        )
        .pipe(
            stylus(stylusConfig).on('error', e => {
                console.log(e.message);
                if (process.env.NODE_ENV === 'production') {
                    throw e;
                }
                cb();
            })
        )
        .pipe(gulp.dest('dist/aliem'));

    if (process.env.NODE_ENV !== 'production') {
        styles = styles.pipe(browserSync.stream());
    }
    return styles;
});

gulp.task('webpack', () => {
    const flags = process.env.NODE_ENV === 'production' ? '-p' : '';
    return exec(
        path.resolve(__dirname, `node_modules/.bin/webpack ${flags}`)
    ).catch(err => {
        console.log(err.stdout);
        throw err;
    });
});

gulp.task(
    'default',
    gulp.series('del', gulp.parallel('static', 'stylus', 'webpack'), done => {
        if (process.env.NODE_ENV === 'production') {
            return done();
        }

        browserSync.init({
            proxy: 'localhost:8080',
            open: false,
        });

        gulp.watch(
            'aliem/styles/**/*.styl',
            { queue: false },
            gulp.series('stylus')
        );

        gulp.watch(['aliem/**/*.php'], gulp.series('static', 'reload'));
    })
);
