require('ts-node/register');
import { resolve } from 'path';
import * as webpack from 'webpack';

export default {
    context: __dirname,
    entry: {
        'social-media-index': './aliem/lib/js/social-media-index/',
    },
    output: {
        filename: '[name].js',
        path: resolve(__dirname, 'dist/aliem/lib/js'),
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
    plugins: [
        new webpack.optimize.ModuleConcatenationPlugin(),
    ]
};
