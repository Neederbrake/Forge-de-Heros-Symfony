# Projet Forge de Heros - Backend Symfony

Application Symfony fullstack + API REST pour la création et gestion de personnages de jeu de rôle.

## Prérequis

- PHP 8.1+ avec les extensions suivantes activées dans php.ini :

```ini
# Pour upload images
extension=fileinfo

# Pour formulaires
extension=intl

# Pour caractères
extension=mbstring

# Pour base SQLite
extension=pdo_sqlite
extension=sqlite3
```

- Composer
- (Optionnel) Symfony CLI

## Installation

1. Cloner le projet et se placer dans le dossier :
```bash
cd forge-heros-back
```

2. Installer les dépendances :
```bash
composer install
```

3. Configurer la base de données :
```bash
# Créer la base vide
php bin/console doctrine:database:create

# Lancer les migrations
php bin/console doctrine:migrations:migrate -n

# Charger les fixtures (données de base)
php bin/console doctrine:fixtures:load -n
```

## Lancement du serveur

```bash
# Démarrer le serveur web (recommandé)
symfony server:start

# Alternative si symfony CLI non disponible
php -S 127.0.0.1:8000 -t public
```

## Commandes utiles

```bash
# Vider le cache en cas de problème
php bin/console cache:clear

# Lister toutes les routes
php bin/console debug:router

# Recharger les fixtures en écrasant les données
php bin/console doctrine:fixtures:load -n --purge-with-truncate
```

## Endpoints principaux

### Interface web (Twig)
- Accueil : http://127.0.0.1:8000/
- Inscription : http://127.0.0.1:8000/register
- Connexion : http://127.0.0.1:8000/login

### Zone admin (ROLE_ADMIN requis)
- Gérer races : http://127.0.0.1:8000/race
- Gérer classes : http://127.0.0.1:8000/character-class
- Gérer compétences : http://127.0.0.1:8000/skill
- Voir tous les utilisateurs : http://127.0.0.1:8000/admin/user

### Zone joueurs (connexion requise)
- Mes héros : http://127.0.0.1:8000/character
- Nouveau héros : http://127.0.0.1:8000/character/new
- Mes groupes : http://127.0.0.1:8000/party

### API REST (format JSON - accès public)
- Point d'entrée : http://127.0.0.1:8000/api/v1/
- Documentation des routes disponibles :
  - `GET /api/v1/races` - Liste des races
  - `GET /api/v1/races/{id}` - Détail d'une race
  - `GET /api/v1/classes` - Liste des classes
  - `GET /api/v1/classes/{id}` - Détail d'une classe avec compétences
  - `GET /api/v1/skills` - Liste des compétences
  - `GET /api/v1/characters` - Liste des personnages (filtrable)
  - `GET /api/v1/characters/{id}` - Détail d'un personnage
  - `GET /api/v1/parties` - Liste des groupes (filtrable)
  - `GET /api/v1/parties/{id}` - Détail d'un groupe

## Comptes de test pré-configurés

- **Admin** : user@gmail.com / mdp: user123
- **Joueur** : user1@gmail.com / mdp: user123

⚠️ Le premier utilisateur qui s'inscrit obtient automatiquement le rôle ADMIN.

## Fonctionnalités

### Système Point Buy
- 27 points à répartir entre 6 caractéristiques
- Chaque stat entre 8 et 15
- Calcul automatique des PV : dé de vie + modificateur Constitution

### Upload d'images
- Support des formats : JPG, JPEG, PNG, GIF, WEBP
- Stockage dans `public/uploads/characters/`

### Filtres et recherches
- Recherche de personnages par nom
- Filtres par classe et race
- Groupes complets ou disponibles

## Notes techniques

- **Base de données** : SQLite (fichier créé dans `var/data.db`)
- **Upload des images** : `public/uploads/characters/`
- **Variables d'environnement** : fichier `.env`
- **Fixtures** : Races, classes et compétences D&D 5e
- **CORS** : Configuré avec NelmioCorsBundle pour l'application React

## Structure du projet

```
src/
├── Controller/    # Contrôleurs web et API
├── Entity/       # Entités Doctrine
├── Form/         # Formulaires Symfony
├── Repository/   # Repositories Doctrine
└── DataFixtures/ # Données de test
```

Les fichiers de configuration IDE (.idea/) sont exclus du versioning.