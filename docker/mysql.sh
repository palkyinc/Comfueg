docker run --name slam-mysql-startup -p 3306:3306 -v /media/proyectos/mysql/Data:/var/lib/mysql --env=MYSQL_ROOT_PASSWORD=@ndres0426 --env=MYSQL_DATABASE=slam --rm image.mysql:1.0