#!/bin/bash
docker run -ti -v "$PWD/site":/usr/share/nginx/ -d -p 8080:80 --name sti_project --hostname sti arubinst/sti:project2018

docker exec -u root sti_project service nginx start

docker exec -u root sti_project service php5-fpm start

docker exec -u root sti_project chown www-data:www-data /usr/share/nginx/databases           
                                                                                             
docker exec -u root sti_project chown www-data:www-data /usr/share/nginx/databases/database.sqlite
