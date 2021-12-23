# Présentation

DefautSymfonyApli est un environement de développement conteneurisé pour une application symfony.

Un certain nombre de dépendance sont déjà installer :

###### dépendance composer
* Symfony CLI
* GIT (Si vous voulez utilisez GIT installer dans le conteneur plutot que celui de votre machine, paramètrer le user.name et user.email)
* npm
* friendsofphp/php-cs-fixer
* spatie/phpunit-watcher
* symfony/webpack-encore-bundle
* orm-fixtures
* fakerphp/faker
* phpstan\phpstan
* easycorp/easyadmin-bundle
* friendsofsymfony/ckeditor-bundle
* helios-ag/fm-elfinder-bundle
* knplabs/knp-paginator-bundle
* knplabs/knp-time-bundle
* vich/uploader-bundle
* twig/string-extra
* twig/extra-bundle
###### dépendance npm
* sass-loader
* sass
* postcss-loader
* autoprefixer
* bootstrap 5
* @popperjs/core

Créer par FIQUET Noah inspiréer du travaille de Yoandev => https://yoandev.co

La taille total des conteneurs est de 1.72 GB

# Pré-requis

un minimum de connaissance sur l'utilisation de docker => https://docs.docker.com & Symfony => https://symfony.com/doc/current/index.html

Installer Git & Docker sur votre machine, les autres pré-requis sont conteneurisés.

Git =>      https://git-scm.com/downloads
Docker =>   https://www.docker.com/get-started


# Installation

### Lorsque Git & Docker sont installées sur votre machine, vous n'avez plus qu'a lancer les commandes suivantes :

1. `git clone https://github.com/Slloth/DefautSymfonyApli.git .` ou `git clone https://github.com/Slloth/DefautSymfonyApli.git myproject`

2. `docker-compose up -d`

3. Modifier la rubrique (# Compte administrateur) provenant du .env pour y enregistrer le compte administrateur du site

4. Créer un fichier .env.local et y ajouter la rubrique (#.env.local exemple) provenant du .env

5. `./install.sh`

6. (Optionnelle) Vous pouvez supprimez la rubrique (# Compte administrateur) dans le .env

###### L'installation peux prendre quelques minutes

### Si vous voulez lancer le serveur web interne de Symfony utiliser cette commande :

7. (Optionnelle) `docker exec -it www_docker_symfony symfony serve -d`

# Liste des ports

* Appache                 80 =>   http://localhost
* PhpMyAdmin              8080 => http://localhost:8080
* MailDev                 8081 => http://localhost:8081
* Serveur Web Symfony     8000 => http://localhost:8000

### Modification des ports

Si vous souhaitez les modifiers, les ports se trouve dans le fichier **docker-compose.yml**

# Démarrage

### Voici les étapes si vous avez déja installer l'application et que vous souhaitez la relancer:

1. Vérifiez bien que docker est démarré sur votre machine
2. Étre dans le répertoire du projet symfony
3. `docker-compose up -d`

# Éteindre les conteneurs

Une fois que vous avez terminé, utiliser la commande. => `docker-compose down`

# Tips

Lorsque vous quittez docker utilisez la commande suivante dans un terminal. => `wsl --shutdown`

Sinon le processus Vmmem va continuer de tourner et il peux consommer beaucoup de ressources.