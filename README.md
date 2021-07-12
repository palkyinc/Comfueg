
## Instalacion del proyecto - Requisitos
Requisitos:
-Windows Server con IIS corriendo
-.NET Framework 4.5.2
-Composer.
-PHP 7.4 al menos.


## Mysql
-Instalar MySQL 8
-Crear un usuario administrador para el Workbrench
-Crear el usuario phpuser para la aplicación
-restaurar Backup de sql\slam20201112.sql


## Configuracion de IIS
-Instalar microsoft web plataform installer para windows server.
    --instalar PHP 7.4 al menos
    --instalar URL rewrite 2.1
-instalar Composer
-Utilizar el siguiente video para la configuracion del IIS: https://youtu.be/hDR1YYaHJzs

## Clonar el repositorio
-git clone https://github.com/palkyinc/Comfueg.git parado en la carpeta C:\inetpub\wwwroot
-correr composer update.
-correr php artisan key:generate

## Rest Resources
- GET /panelTest/ip
- GET /allPanels
- GET /adminControlPanelNodos
- GET /Cliente/id
- GET /CodigoDeArea/{id}
- GET /CodigoDeArea/ (todos los codigos)
- GET /CodigoDeArea/Codigo/{codigo} (todos los codigos)

