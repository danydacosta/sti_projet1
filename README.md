# Projet 1 STI


## Installation sur Docker

Le script `start.sh` peut être utilisé afin d'automatiser le processus de création du container et démarrage de service. Il est aussi possible d'utiliser les commandes ci-dessous :

```
docker run -ti -v "$PWD/site":/usr/share/nginx/ -d -p 8080:80 --name sti_project --hostname sti arubinst/sti:project2018

docker exec -u root sti_project service nginx start

docker exec -u root sti_project service php5-fpm start
```

L'application est ensuite accessible à l'adresse http://localhost:8080/

## Utilisation de l'application

Deux utilisateurs existent déjà dans la base de données :

| Login   | Mdp  | Admin |
|---------|------|-------|
| dupontj | pass | non   |
| doej    | pass | oui   |
