import vue from '@vitejs/plugin-vue'
import ViteRestart from 'vite-plugin-restart';
import viteCompression from 'vite-plugin-compression';
import eslintPlugin from 'vite-plugin-eslint';
import { nodeResolve } from '@rollup/plugin-node-resolve';
import * as path from 'path';

// https://vitejs.dev/config/
export default ({ command }) => ({
    base: command === 'serve' ? '' : '/dist/',
    build: {
        emptyOutDir: true,
        manifest: true,
        outDir: '../src/web/assets/dist',
        rollupOptions: {
            input: {
                main: 'src/js/main.ts',
                checkbox: 'src/js/checkbox-search-field.ts',
            },
            output: {
                sourcemap: true
            },
        }
    },
    plugins: [
        nodeResolve({
            moduleDirectories: [
                path.resolve('./node_modules'),
            ],
        }),
        ViteRestart({
            reload: [
                '../src/templates/**/*',
            ],
        }),
        vue(),
    ],
    publicDir: '../src/web/assets/public',
    resolve: {
        alias: {
            '~': path.resolve(__dirname, './src'),
            vue: 'vue/dist/vue.esm-bundler.js',
        },
        preserveSymlinks: true,
    },
    server: {
        fs: {
            strict: false
        },
        host: '0.0.0.0',
        origin: 'http://localhost:3752',
        port: 3752,
        strictPort: true,
    }
});
