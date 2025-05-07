import vue from '@vitejs/plugin-vue2';
import { defineConfig } from 'vite';

export default defineConfig({
  plugins: [vue()],
  server: {
    port: 5174,
    proxy: {
      '/api': {
        target: 'http://127.0.0.1:8000', // Puerto donde corre Laravel
        changeOrigin: true,
        secure: false
      }
    }
  }
});
