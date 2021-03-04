import { defineConfig } from 'vite'
import reactRefresh from '@vitejs/plugin-react-refresh'
// `yarn build` pour generer les assets du projet
// https://vitejs.dev/config/
export default defineConfig({
  plugins: [reactRefresh()],
  root: './assets',
  base: '/assets/',
  build: {
    manifest: true, // faciliter la lecture des fichier avec les hash
    assetsDir: '',  // Pour dire pas dossier à ce niveau
    outDir: '../public/assets/',
    rollupOptions: {
      output: {
        manualChunks: undefined, // remove le fichier vendor.js et laisser un seul point d'entrer
      },
      input: {
        'main.jsx': './assets/main.jsx'
      }
    }
  }
})
