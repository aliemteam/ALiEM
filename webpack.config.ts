require('ts-node/register');
import { resolve } from 'path';
import * as webpack from 'webpack';

const plugins = [
    new webpack.optimize.ModuleConcatenationPlugin(),
];

export default <webpack.Configuration>{
    context: __dirname,
    entry: {
        'social-media-index': './aliem/js/social-media-index/',
    },
    output: {
        filename: '[name].js',
        path: resolve(__dirname, 'dist/aliem/js'),
    },
    resolve: {
        extensions: ['*', '.ts', '.tsx', '.js', '.jsx', '.css'],
    },
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                use: ['babel-loader', 'ts-loader'],
            },
            {
                test: /\.jsx?$/,
                use: ['babel-loader'],
            },
            {
                test: /\.css$/,
                use: ['style-loader', 'css-loader'],
            }
        ],
    },
    plugins,
};
