# Projet 1 STI


## 1. Installation Docker

`docker run -ti -v "$PWD/site":/usr/share/nginx/ -d -p 8080:80 --name sti_project --hostname sti arubinst/sti:project2018`

`docker exec -u root sti_project service nginx start`

`docker exec -u root sti_project service php5-fpm start`


## Répartition tâches

### Dany
  index -> affichage des messages liste
  détails -> détails d'un message
  nouveau message -> écrire un nouveau message

### Stefan
  configuration user -> changement du mdp
  admin -> CRUD utilisateurs
    new user
    update user
  
