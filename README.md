# Projet PHP avec une architecture Modèle-Vue-Contrôleur

## Assets front-end avec Vite

Le projet inclut désormais [Vite](https://vitejs.dev/) pour compiler les assets JavaScript modernes.

```bash
npm install
npm run dev    # lance le serveur Vite sur http://localhost:5173
npm run build  # génère les fichiers optimisés dans public/build
```

Lorsque le serveur de développement est actif, le template charge automatiquement `@vite/client` ainsi que le module principal pour offrir le rechargement à chaud. Sans serveur Vite, assurez-vous d'exécuter `npm run build` afin que `public/build/main.js` soit disponible et servi par PHP.

## Configuration de l'environnement

Les informations sensibles (connexion à la base de données, etc.) doivent être définies dans un fichier `.env` placé à la racine du projet. Un exemple est fourni dans `.env.example` :

```bash
cp .env.example .env
```

Vous pouvez ensuite adapter les variables (`DB_HOST`, `DB_NAME`, etc.) à votre environnement. Le fichier `.env` est chargé automatiquement lors de chaque requête.
