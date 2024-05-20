mkdir /media/proyectos/aircontrol
mkdir /media/proyectos/aircontrol/pgsql
mkdir /media/proyectos/aircontrol/pgsql/data
mkdir /media/proyectos/Coredns
cp /media/proyectos/Comfueg/docker/repositories /etc/apk/repositories
cp /media/proyectos/Comfueg/docker/Coredns /media/proyectos/Coredns
apk add docker
addgroup soporte docker
rc-update add docker boot
service docker start
apk add docker-compose
apk add zip
docker volume create db-aircontrol
##docker volume create db_slam
docker build -f Dockerfile.aircontrol -t image.aircontrol:1.0 .
docker build -f Dockerfile.mysql -t image.mysql:1.0 .
docker build -f Dockerfile.php-apache -t image.php-apache:1.4 .
docker build -f Dockerfile.coredns -t image.coredns:1.0 .
docker run --rm -v db-aircontrol:/bdatos image.aircontrol:1.0
cp -RT /opt/Ubiquiti/AirControl2/pgsql/data /bdatos