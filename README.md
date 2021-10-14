# Projet 1 STI


## Installation sur Docker

Le script `start.sh` peut être utilisé afin d'automatiser le processus de création du container et démarrage de service. Il est aussi possible d'utiliser les commandes ci-dessous :

```
docker run -ti -v "$PWD/site":/usr/share/nginx/ -d -p 8080:80 --name sti_project --hostname sti arubinst/sti:project2018

docker exec -u root sti_project service nginx start

docker exec -u root sti_project service php5-fpm start

docker exec -u root sti_project chown www-data:www-data /usr/share/nginx/databases           
                                                                                             
docker exec -u root sti_project chown www-data:www-data /usr/share/nginx/databases/database.sqlite
```

L'application est ensuite accessible à l'adresse http://localhost:8080/

## Utilisation de l'application

Deux utilisateurs existent déjà dans la base de données :

| Login   | Mdp  | Admin |
|---------|------|-------|
| dupontj | pass | non   |
| doej    | pass | oui   |


## Remarques  

Lorsque nous récupérons des données entrées par l'utilisateur, nous avons fait aucun escape de caractères spéciaux. Ceci veut dire que si l'on insère des apostrophes, par exemple dans un contenu email, des erreurs peuvent se produire... sûrement utile pour la suite lors des attaques :)
