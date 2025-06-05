# JPO Connect 🚀

👋 **Bonjour ! Ce projet est actuellement en cours d'élaboration.** La structure et les fonctionnalités décrites ci-dessous représentent la trame de base et sont susceptibles d'évoluer. Merci de votre compréhension !

## Introduction 🎯

Chaque année, La Plateforme organise des journées portes ouvertes (JPO) pour recruter ses futurs étudiants. Comme chaque année, l'équipe marketing utilise la suite Google pour organiser ses journées, ce qui s'avère laborieux...

**JPO Connect** est une plateforme web conçue pour simplifier et centraliser la gestion des inscriptions et l'administration des JPO de La Plateforme. Elle vise à offrir une meilleure expérience tant pour les futurs étudiants et leurs parents que pour l'équipe de recrutement [1].

## Objectifs et Fonctionnalités Principales ✨

- **Pour les futurs étudiants :**
  - Inscription et désinscription faciles aux JPO.
  - Notifications par email 📧 pour les rappels d'événements.
  - Moteur de recherche 🔍 pour trouver des JPO dans tous nos établissements (y compris Cannes, Martigues et Paris).
  - Système de commentaires/avis 💬 pour chaque JPO.
- **Pour l'équipe de recrutement (Tableau de Bord Administrateur) :**
  - Gestion complète des JPO : ajout, modification, suppression.
  - Gestion de la capacité d'inscrits par JPO.
  - Accès à des statistiques 📊 : nombre de visiteurs inscrits, nombre de présents, etc.
  - Modération des commentaires/avis (répondre, approuver, supprimer).
  - Possibilité de modifier certains contenus du site (sessions à venir, infos pratiques).
  - Gestion des rôles utilisateurs 👥 pour l'équipe marketing (directeur, responsable, salariés) avec des permissions différenciées.

## Technologies Utilisées 💻

- **Frontend :** ReactJS
- **Backend :** PHP natif (Programmation Orientée Objet, Classes, PDO)
- **Base de données :** MySQL
- **Serveur Web :** Nginx

## Structure du Projet 📁

```
jpo-connect/
├── backend/             # Code source PHP (POO, PDO)
│   ├── api/             # Point d’entrée de l’API (index.php)
│   ├── classes/         # Contrôleurs, Modèles, autres classes métier
│   ├── config/          # Fichiers de configuration (database.php)
│   └── core/            # Classes de base (Router.php, etc.)
├── frontend/            # Code source ReactJS
│   ├── public/          # Fichiers statiques
│   └── src/             # Composants, pages, hooks, etc.
├── docs/                # Documents de conception (PDF)
│   ├── wireframes.pdf
│   ├── maquettes_graphiques.pdf
│   └── MCD_MLD.pdf
└── README.md            # Ce fichier
```

## Prérequis 🛠️

Avant de commencer, assurez-vous d'avoir installé les logiciels suivants sur votre machine :

- Node.js (pour la partie ReactJS, `npm` ou `yarn`)
- PHP (version 7.4 ou supérieure recommandée)
- Nginx
- MySQL

Vous aurez besoin d'une pile LEMP (Linux, Nginx, MySQL, PHP) fonctionnelle.

## Installation et Lancement 🚀

1.  **Cloner le dépôt :**

    ```
    git clone https://github.com/olivia-dondas/jpo-connect.git
    cd jpo-connect
    ```

2.  **Configuration du Backend :**

    - **Installer les composants LEMP :** Si ce n'est pas déjà fait, installez Nginx, MySQL, PHP et `php-fpm` sur votre système.
      ```
      # Exemple pour Ubuntu
      sudo apt update
      sudo apt install nginx mysql-server php-fpm php-mysql
      ```
    - **Configurer Nginx pour PHP :** Modifiez la configuration de votre serveur Nginx (généralement dans `/etc/nginx/sites-available/`) pour qu'il transmette les requêtes PHP à PHP-FPM. Voici un exemple de bloc `server` (adaptez `your_domain` et le chemin vers `root`) :

      ```
      server {
          listen 80;
          server_name your_domain_or_ip; # Ex: localhost ou jpo-connect.local
          root /chemin/vers/votre/projet/jpo-connect/backend/api; # Point d'entrée API

          index index.php;

          location / {
              try_files $uri $uri/ /index.php?$query_string;
          }

          location ~ \.php$ {
              include snippets/fastcgi-php.conf;
              fastcgi_pass unix:/var/run/php/phpX.Y-fpm.sock; # Adaptez X.Y à votre version PHP
              fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
              include fastcgi_params;
          }

          location ~ /\.ht { # Bien que Nginx n'utilise pas .htaccess, c'est une bonne pratique de le bloquer
              deny all;
          }
      }
      ```

      Activez la configuration (ex: `sudo ln -s /etc/nginx/sites-available/jpo-connect /etc/nginx/sites-enabled/`) et redémarrez Nginx (`sudo systemctl restart nginx`).

    - **Configuration de la Base de Données :**
      - Naviguez vers `jpo-connect/backend/config/`.
      - Créez `database.php` à partir de `database.example.php` (si fourni) ou directement.
      - Renseignez vos identifiants de connexion MySQL.
      - Créez une base de données MySQL pour JPO Connect et importez la structure (un fichier `.sql` sera fourni ultérieurement).
    - **Vérifiez l'accès à l'API :** Après configuration, une route de test de l'API (ex: `http://your_domain_or_ip/test`) devrait fonctionner.

3.  **Configuration du Frontend :**
    - Naviguez vers `jpo-connect/frontend/` :
      ```
      cd frontend
      ```
    - Installez les dépendances :
      ```
      npm install
      # ou yarn install
      ```
    - Lancez le serveur de développement React :
      ```
      npm start
      # ou yarn start
      ```
      L'application frontend devrait être accessible sur `http://localhost:3000`.

## Utilisation 💡

- **Site Public :** Accédez à `http://localhost:3000` pour explorer les fonctionnalités pour les futurs étudiants.
- **Tableau de Bord Administrateur :** L'accès se fera via une route dédiée (ex: `/admin/login`) après authentification.

## Documentation de Conception 📝

Tous les documents relatifs à la conception du projet (wireframes, maquettes graphiques, Modèle Conceptuel de Données, Modèle Logique de Données) sont disponibles au format PDF dans le dossier `/docs/`.

## Déploiement 🌐

Le projet final est destiné à être hébergé sur Plesk [1].

## Présentation et Évaluation 🎤

Ce projet sera évalué lors d'un pitch oral présentant :

- La démarche de création de la maquette (Figma).
- La conception de la base de données.
- La réalisation technique du projet.
- Les axes d'amélioration potentiels.
- Une démonstration en direct du projet.
  Des slides (Google Slides ou équivalent) devront être préparées pour cette présentation [1].

## Pour Aller Plus Loin... 🌟

- Mise en place de tests unitaires avec PHPUnit (pour le backend) et Jest (pour le frontend).
- Permettre l'inscription à la plateforme via des réseaux sociaux (Google, LinkedIn...).
