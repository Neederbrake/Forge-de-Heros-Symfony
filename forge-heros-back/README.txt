# demarre le serveur web symfony
symfony server:start

# demarre le serveur via php si symfony bug
php -S 127.0.0.1:8000 -t public

# vide le cache
php bin/console cache:clear

# liste toutes les routes
php bin/console debug:router

L'Entrée (Accueil & Connexion)

Accueil du site : http://127.0.0.1:8000/

Créer un compte : http://127.0.0.1:8000/register (pratique pour tester un nouveau joueur)

Se connecter : http://127.0.0.1:8000/login

La zone d'Administration (Le Maître du Jeu)
Il faut être connecté avec un compte qui possède le ROLE_ADMIN pour y accéder, sinon accès refusé !

Gérer les Races : http://127.0.0.1:8000/race

Gérer les Classes : http://127.0.0.1:8000/character-class

Gérer les Compétences : http://127.0.0.1:8000/skill

La zone des Joueurs
N'importe quel utilisateur connecté y a accès.

Liste de mes héros : http://127.0.0.1:8000/character

Créer un nouveau héros : http://127.0.0.1:8000/character/new


admin: user
user@gmail.com
mdp=user123

autre: user1
user1@gmail.com
mdp=user123

# installe les dependances
composer install

# cree la base vide
php bin/console doctrine:database:create

# lance les migrations
php bin/console doctrine:migrations:migrate -n


dans le php.init
// pour l upload des images
extension=fileinfo

// pour les formulaires symfony
extension=intl

// pour les caracteres speciaux
extension=mbstring

// pour la base de donnees sqlite
extension=pdo_sqlite
extension=sqlite3
