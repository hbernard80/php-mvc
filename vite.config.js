import { defineConfig } from 'vite';
import { resolve } from 'node:path';

const projectRoot = __dirname;
const assetsDir = resolve(projectRoot, 'assets');
const publicDir = resolve(projectRoot, 'public');

export default defineConfig({
  root: assetsDir,
  base: '/build/',
  publicDir: false,
  server: {
    port: 5173,
    strictPort: true,
    origin: 'http://localhost:5173'
  },
  build: {
    outDir: resolve(publicDir, 'build'),
    emptyOutDir: true,
    manifest: false,
    rollupOptions: {
      input: resolve(assetsDir, 'main.js'),
      output: {
        entryFileNames: 'main.js',
        assetFileNames: '[name][extname]'
      }
    }
  }
});
