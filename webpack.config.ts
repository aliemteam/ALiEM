import { TsConfigPathsPlugin } from 'awesome-typescript-loader';
import { resolve } from 'path';
import * as webpack from 'webpack';

const IS_PRODUCTION = process.env.NODE_ENV === 'production';

const sharedPlugins: webpack.Plugin[] = [
    new webpack.optimize.ModuleConcatenationPlugin(),
    new webpack.NoEmitOnErrorsPlugin(),
    new webpack.DefinePlugin({
        __DEV__: JSON.stringify(!IS_PRODUCTION),
        'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV),
    }),
];

const devPlugins: webpack.Plugin[] = [...sharedPlugins];

const productionPlugins: webpack.Plugin[] = [
    ...sharedPlugins,
    new webpack.optimize.UglifyJsPlugin({
        beautify: false,
        mangle: {
            screw_ie8: true,
            keep_fnames: true,
        },
        compress: {
            screw_ie8: true,
        },
        comments: false,
    }),
    new webpack.LoaderOptionsPlugin({
        minimize: true,
        debug: false,
    }),
];

export default <webpack.Configuration>{
    watch: !IS_PRODUCTION,
    watchOptions: {
        ignored: /(node_modules|gulpfile|dist|lib|webpack.config)/,
    },
    // context: __dirname,
    devtool: IS_PRODUCTION ? 'cheap-module-source-map' : 'source-map',
    entry: {
        'social-media-index': './src/js/social-media-index/',
        'header-main': './src/js/headers/main/',
        'header-posts': './src/js/headers/posts/',
        'image-lazy-load': './src/js/image-lazy-load/',
    },
    output: {
        filename: '[name].js',
        path: resolve(__dirname, 'dist/js'),
    },
    resolve: {
        extensions: ['*', '.ts', '.tsx', '.js', '.jsx'],
        modules: [resolve(__dirname, 'src'), 'node_modules'],
        plugins: [new TsConfigPathsPlugin()],
    },
    externals: {
        react: 'React',
        'react-dom': 'ReactDOM',
    },
    plugins: IS_PRODUCTION ? productionPlugins : devPlugins,
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                exclude: /(?:__tests__|node_modules)/,
                use: ['awesome-typescript-loader'],
            },
            {
                test: /\.jsx?$/,
                exclude: /node_modules/,
                use: ['babel-loader'],
            },
            {
                test: /\.css$/,
                use: ['style-loader', 'css-loader'],
            },
            {
                test: /\.scss$/,
                use: [
                    {
                        loader: 'style-loader',
                    },
                    {
                        loader: 'css-loader',
                        options: {
                            sourceMap: true,
                        },
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: true,
                        },
                    },
                ],
            },
        ],
    },
};
