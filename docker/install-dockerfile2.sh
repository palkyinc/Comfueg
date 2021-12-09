docker build -f Dockerfile.aircontrol -t image.aircontrol:1.0 .
docker build -f Dockerfile.mysql -t image.mysql:1.0 .
docker build -f Dockerfile.php-apache -t image.php-apache:1.4 .