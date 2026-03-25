# projet forge de heros - backend symfony

voici le guide complet pour configurer, installer et tester l application symfony et son api rest.

## 1. configuration php (php.ini)
avant de lancer le projet, verifiez que ces extensions sont bien activees (sans le point-virgule devant) dans votre fichier `php.ini` :

```ini
// pour l upload des images
extension=fileinfo

// pour les formulaires symfony
extension=intl

// pour les caracteres speciaux
extension=mbstring

// pour la base de donnees sqlite
extension=pdo_sqlite
extension=sqlite3

# installe les dependances
composer install

# cree la base vide
php bin/console doctrine:database:create

# lance les migrations
php bin/console doctrine:migrations:migrate -n

# charge la seed (races, classes, competences)
php bin/console doctrine:fixtures:load -n

# demarre le serveur web symfony (methode recommandee)
symfony server:start

# demarre le serveur via php si symfony bug
php -S 127.0.0.1:8000 -t public

# vide le cache en cas de probleme d affichage
php bin/console cache:clear

# liste toutes les routes disponibles sur le site
php bin/console debug:router

l entree (accueil & connexion)
accueil du site : http://127.0.0.1:8000/

creer un compte : http://127.0.0.1:8000/register (pratique pour tester un nouveau joueur)

se connecter : http://127.0.0.1:8000/login

la zone d administration (le maitre du jeu)
il faut etre connecte avec un compte qui possede le role_admin pour y acceder, sinon acces refuse !

gerer les races : http://127.0.0.1:8000/race

gerer les classes : http://127.0.0.1:8000/character-class

gerer les competences : http://127.0.0.1:8000/skill

la zone des joueurs
n importe quel utilisateur connecte y a acces.

liste de mes heros : http://127.0.0.1:8000/character

creer un nouveau heros : http://127.0.0.1:8000/character/new

l api rest (partie b)
accessible publiquement pour l application react.

point d entree principal : https://www.google.com/search?q=http://127.0.0.1:8000/api/v1/

5. comptes de test preconfigures
voici les identifiants pour tester rapidement les differents roles sur le site :

compte administrateur :

email : user@gmail.com

mdp : user123

compte joueur classique :

email : user1@gmail.com

mdp : user123