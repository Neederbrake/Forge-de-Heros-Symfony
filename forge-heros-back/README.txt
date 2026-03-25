# projet forge de heros - backend symfony et theorie

ce readme contient les instructions du projet et les concepts cles du cours sur symfony et les api rest.

## prerequis (php.ini)
activez ces extensions dans php.ini (enlevez le point-virgule) :

```ini
// pour upload images
extension=fileinfo

// pour formulaires
extension=intl

// pour caracteres
extension=mbstring

// pour base sqlite
extension=pdo_sqlite
extension=sqlite3

4. installation de la base de donnees
ouvrez un terminal a la racine du projet :

Bash
# installe dependances
composer install

# cree base vide
php bin/console doctrine:database:create

# lance migrations
php bin/console doctrine:migrations:migrate -n

# charge fausses donnees
php bin/console doctrine:fixtures:load -n

5. lancement du serveur
Bash
# demarre serveur web
symfony server:start

# alternative si symfony bug
php -S 127.0.0.1:8000 -t public

# vide cache si probleme
php bin/console cache:clear

# liste toutes les routes
php bin/console debug:router

6. adresses du site (endpoints)
interface web (twig)
accueil : http://127.0.0.1:8000/

creer compte : http://127.0.0.1:8000/register

connexion : http://127.0.0.1:8000/login

zone admin (role_admin requis)
gerer races : http://127.0.0.1:8000/race

gerer classes : http://127.0.0.1:8000/character-class

gerer competences : http://127.0.0.1:8000/skill

zone joueurs (connexion requise)
mes heros : http://127.0.0.1:8000/character

nouveau heros : http://127.0.0.1:8000/character/new

api rest (format json)
point d entree : https://www.google.com/search?q=http://127.0.0.1:8000/api/v1/

7. comptes de test preconfigures
admin : user@gmail.com / mdp: user123

joueur : user1@gmail.com / mdp: user123