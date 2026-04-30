# Guide d'Installation pour le Chef de Projet

Ce projet a été configuré pour être lancé avec un minimum d'effort grâce à **Docker** et **Laravel Sail**.

## Prérequis

Le seul logiciel à installer sur votre ordinateur est **Docker Desktop**.
1. Téléchargez-le ici : [https://www.docker.com/products/docker-desktop/](https://www.docker.com/products/docker-desktop/)
2. Installez-le et assurez-vous qu'il est en cours d'exécution (vous devriez voir une petite icône de baleine dans la barre des tâches).

## Installation Rapide (Windows)

1. Récupérez le dossier du projet (via ZIP ou Git).
2. Ouvrez le dossier dans votre explorateur de fichiers.
3. Double-cliquez sur le fichier `setup-pm.bat`.
4. Une fenêtre noire s'ouvrira et configurera tout automatiquement (téléchargement des serveurs, création de la base de données, etc.).
5. Une fois terminé, le message "CONFIGURATION TERMINEE !" apparaîtra.

## Comment accéder au projet

Une fois l'installation finie, ouvrez votre navigateur et allez à l'adresse suivante :
[**http://localhost**](http://localhost)

## Arrêter le projet

Pour arrêter les serveurs, vous pouvez soit fermer la fenêtre Docker Desktop, soit ouvrir un terminal dans le dossier et taper :
```bash
./vendor/bin/sail down
```

---
*Note : La première installation peut prendre quelques minutes le temps que Docker télécharge les images nécessaires (PHP, MySQL).*
