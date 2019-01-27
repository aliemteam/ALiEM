declare module 'browser-sync-webpack-plugin';
declare module 'webpack-fix-style-only-entries';

declare module '*.scss' {
    const content: Record<string, string>;
    export = content;
}
