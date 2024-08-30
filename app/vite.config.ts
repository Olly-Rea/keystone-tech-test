/* eslint-disable import/no-extraneous-dependencies */
import __dirname from 'path';
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: [{
      find: '@',
      replacement: __dirname.resolve('./src'),
    }],
  },
});
