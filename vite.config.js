import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import pkg from './package.json';
import dayjs from 'dayjs';

const __APP_INFO__ = {
  releaseVersion: pkg['app-version'],
  lastBuildDate: dayjs().format('YYYY-MM-DD HH:mm:ss'),
};

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/admin/app.ts',
        'resources/brand/app.ts',
        'resources/app/app.ts',
        'resources/app/report.ts',
        'resources/html/app.ts',
      ],
      ssr: 'resources/brand/ssr.ts',
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
  ],
  define: {
    __APP_INFO__: JSON.stringify(__APP_INFO__),
  },
  resolve: {
    alias: [{ find: '@', replacement: path.resolve(__dirname, 'resources') }],
  },
});
