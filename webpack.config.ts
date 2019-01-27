import path from 'path';

import { CheckerPlugin, TsConfigPathsPlugin } from 'awesome-typescript-loader';
import BrowserSyncPlugin from 'browser-sync-webpack-plugin';
import CopyWebpackPlugin from 'copy-webpack-plugin';
import imagemin from 'imagemin';
import imageminPngquant from 'imagemin-pngquant';
import imageminSvgo from 'imagemin-svgo';
import MiniCssExtractPlugin from 'mini-css-extract-plugin';
import rimraf from 'rimraf';
import { Configuration, Plugin } from 'webpack';
import FixStyleOnlyEntriesPlugin from 'webpack-fix-style-only-entries';

import { version as VERSION } from './package.json';

export default (_: any, argv: any): Configuration => {
    const IS_PRODUCTION = argv.mode === 'production';

    // Clean out dist directory
    rimraf.sync(path.join(__dirname, 'dist', '*'));

    const plugins = new Set<Plugin>([
        new FixStyleOnlyEntriesPlugin({ silent: !IS_PRODUCTION }),
        new MiniCssExtractPlugin(),
        new CopyWebpackPlugin([
            {
                from: 'vendor/**',
            },
            {
                from: 'functions.php',
                transform(content) {
                    return content.toString().replace(/{{VERSION}}/g, VERSION);
                },
            },
            {
                from: '**/*.php',
            },
            {
                from: '**/*.json',
                transform(content) {
                    return JSON.stringify(JSON.parse(content.toString()));
                },
            },
            {
                from: 'assets/**',
                transform(content: any, contentPath) {
                    if (!IS_PRODUCTION) {
                        return content;
                    }
                    switch (path.extname(contentPath)) {
                        case '.svg':
                            return imagemin.buffer(content, {
                                plugins: [imageminSvgo()],
                            });
                        case '.png':
                            return imagemin.buffer(content, {
                                plugins: [imageminPngquant({ strip: true })],
                            });
                        default:
                            return content;
                    }
                },
            },
        ]),
        new CheckerPlugin(),
    ]);

    if (!IS_PRODUCTION) {
        plugins.add(
            new BrowserSyncPlugin({
                proxy: 'localhost:8080',
                open: false,
                reloadDebounce: 2000,
                port: 3005,
                notify: false,
            }),
        );
    }

    return {
        devtool: IS_PRODUCTION ? 'source-map' : 'cheap-module-eval-source-map',
        watchOptions: {
            ignored: /(node_modules|__tests__)/,
        },
        context: path.resolve(__dirname, 'src'),
        externals: {
            react: 'React',
            'react-dom': 'ReactDOM',
        },
        entry: {
            // Style entrypoints
            admin: './styles/admin?global',
            editor: './styles/editor?global',
            style: './styles/style?global',

            // JS entrypoints
            'js/social-media-index': './js/social-media-index/',
            'js/header-main': './js/headers/main/',
            'js/header-posts': './js/headers/posts/',
            'js/image-lazy-load': './js/image-lazy-load/',
        },
        output: {
            filename: '[name].js',
            path: path.resolve(__dirname, 'dist'),
        },
        resolve: {
            alias: {
                styles: path.resolve(__dirname, 'src/styles'),
            },
            modules: [path.resolve(__dirname, 'src'), 'node_modules'],
            extensions: ['*', '.ts', '.tsx', '.js', '.scss'],
            plugins: [new TsConfigPathsPlugin()],
        },
        plugins: [...plugins],
        stats: IS_PRODUCTION ? 'normal' : 'minimal',
        module: {
            rules: [
                {
                    test: /\.tsx?$/,
                    sideEffects: false,
                    rules: [
                        {
                            loader: 'babel-loader',
                        },
                        {
                            loader: 'awesome-typescript-loader',
                            options: {
                                silent: argv.json,
                                useCache: !IS_PRODUCTION,
                                cacheDirectory: path.resolve(
                                    __dirname,
                                    'node_modules/.cache/awesome-typescript-loader',
                                ),
                                reportFiles: [
                                    '**/*.{ts,tsx}',
                                    '!**/__tests__/**',
                                ],
                            },
                        },
                    ],
                },
                {
                    test: /\.scss$/,
                    rules: [
                        {
                            use: [MiniCssExtractPlugin.loader],
                        },
                        {
                            oneOf: [
                                {
                                    resourceQuery: /global/,
                                    use: [
                                        {
                                            loader: 'css-loader',
                                            options: {
                                                url: false,
                                                importLoaders: 2,
                                                modules: false,
                                            },
                                        },
                                    ],
                                },
                                {
                                    use: [
                                        {
                                            loader: 'css-loader',
                                            options: {
                                                url: false,
                                                importLoaders: 2,
                                                modules: true,
                                                camelCase: 'only',
                                                localIdentName:
                                                    '[name]__[local]___[hash:base64:5]',
                                            },
                                        },
                                    ],
                                },
                            ],
                        },
                        {
                            use: [
                                {
                                    loader: 'postcss-loader',
                                    options: {
                                        ident: 'postcss',
                                        plugins: [
                                            require('postcss-preset-env')(),
                                            ...(IS_PRODUCTION
                                                ? [require('cssnano')()]
                                                : []),
                                        ],
                                    },
                                },
                                {
                                    loader: 'sass-loader',
                                    options: {
                                        includePaths: ['src/css'],
                                    },
                                },
                            ],
                        },
                    ],
                },
                {
                    test: /\.css$/,
                    rules: [
                        {
                            use: [MiniCssExtractPlugin.loader],
                        },
                        {
                            loader: 'css-loader',
                            options: {
                                importLoaders: 1,
                                modules: false,
                            },
                        },
                        {
                            loader: 'postcss-loader',
                            options: {
                                ident: 'postcss',
                                plugins: [
                                    require('postcss-preset-env')(),
                                    ...(IS_PRODUCTION
                                        ? [require('cssnano')()]
                                        : []),
                                ],
                            },
                        },
                    ],
                },
            ],
        },
    };
};
