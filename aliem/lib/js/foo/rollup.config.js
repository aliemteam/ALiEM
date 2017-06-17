import babel from 'rollup-plugin-babel';
import resolve from 'rollup-plugin-node-resolve';

export default {
    entry: './index.js',
    format: 'iife',
    dest: 'bundle.js',
    plugins: [
        resolve(),
        babel({
            exclude: 'node_modules/**',
        }),
    ]
}
