# Projet PHP avec une architecture Modèle-Vue-Contrôleur

## Assets front-end avec Vite

Le projet inclut désormais [Vite](https://vitejs.dev/) pour compiler les assets JavaScript modernes.

```bash
npm install
npm run dev    # lance le serveur Vite sur http://localhost:5173
npm run build  # génère les fichiers optimisés dans public/build
```

Lorsque le serveur de développement est actif, le template charge automatiquement `@vite/client` ainsi que le module principal pour offrir le rechargement à chaud. Sans serveur Vite, assurez-vous d'exécuter `npm run build` afin que `public/build/main.js` soit disponible et servi par PHP.
