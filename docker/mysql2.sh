docker exec -it slam-mysql-startup mysql --user=root --password=@ndres0426 --database=slam --execute="CREATE USER 'phpuser'@'%' IDENTIFIED WITH mysql_native_password BY 'ChauWispr0'"
docker exec -it slam-mysql-startup mysql --user=root --password=@ndres0426 --database=slam --execute="GRANT ALL ON slam.* TO 'phpuser'@'%';"
docker exec -it slam-mysql-startup mysql --user=root --password=@ndres0426 --database=slam --execute="FLUSH PRIVILEGES;"
docker stop slam-mysql-startup