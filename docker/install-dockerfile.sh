cp /media/proyectos/Comfueg/docker/repositories /etc/apk/repositories
apk add docker
addgroup soporte docker
rc-update add docker boot
service docker start
apk add docker-compose
apk add zip
docker volume create db_aircontrol
docker volume create db_slam
docker build -f Dockerfile.aircontrol -t image.aircontrol:1.0 .
docker build -f Dockerfile.mysql -t image.mysql:1.0 .
docker build -f Dockerfile.php-apache -t image.php-apache:1.4 .