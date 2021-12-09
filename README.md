
## Instalacion del proyecto - Requisitos
Requisitos:
-Computadora/servidor
-Alpine Linux instalable. CD, DVD o pendrive. https://dl-cdn.alpinelinux.org/alpine/v3.15/releases/x86_64/alpine-extended-3.15.0-x86_64.iso
-Conexión a internet
-Putty instalado en PC de Administración


## Instalación Alpine
1.  Bootear desde medio removible con el Alpine Linux. Loguearse con el usuario: root
2.  Correr: setup-alpine
3.  Select Keyboard: latam, variante: latam
4.  LocalHost: alpine-slam
5.  Inicializar eth0: enter
6.  IP: 10.10.0.245
7.  Netmask: 255.255.0.0
8.  Gateway: 10.10.0.26
9.  Si existen mas interfaces seleccionar: done y luego: no
10. DNS domain name: enter
11. DNS nameserver: 10.10.0.26
12.  Ingresar Pass. NOTA: el password será el del usuario root. Anotarlo de manera clara y guardarlo en un lugar seguro.
13. Timezone: America
14. Sub_timezone: Buenos_Aires
15. Proxy URL: enter
16. NTP client: enter
17. Enter mirror number: r
18. Which SSH server: enter
19. Which disk: sda
20. How would you like use it?: sys
21. reboot

## Descargar proyecto
1.  Ingresar con root y contraseña seteada en el punto 12 anterior.
2.  Crear la carpeta donde descargaremos el proyecto:
    a.  mkdir /media/proyectos
    b.  cd /media/proyectos
    c.  apk add git
    d.  git clone https://github.com/palkyinc/Comfueg.git

## Habilitar ssh y crear usuario para acceso.
1.  cd Comfueg/docker
    sh install-ssh.sh
    ATENCION: Escribir para el usuario soporte. Mantenerga segura y a resguardo.
2.  Comporbacion de que funcionan los permisos sudo:
    a.  iniciar conexion con Putty (IP 10.10.0.245 puerto 2233)
    b.  login con las credenciales de soporte
    c.  corre el siguiente comando: apk update
    d.  Solicitará que se ingrese la contraseña sudo que la misma de soporte.
    e.  Si corre sin ningun error de sudo que es que todo está OK.
Conclusiones:
1.  A partir de ahora todo se puede trabajar a traves de PuTTY. No usar mas la consola con teclado y monitor.
2.  El usuario root no tiene permisos para conectarse por PuTTy, solo por consola. hay que usar el usuario soporte.

## Habilitar Firewall
    sudo sh /media/proyectos/Comfueg/docker/install-awall.sh

## Crear las imagenes y contenedores de Docker
    sudo sh /media/proyectos/Comfueg/docker/install-dockerfile.sh
    sudo sh /media/proyectos/Comfueg/docker/install-dockerfile2.sh
    sudo sh mysql.sh
    sudo sh mysql2.sh

## Descargar backup y restaurar

## Rest Resources
- GET /panelTest/ip
- GET /allPanels
- GET /adminControlPanelNodos
- GET /Cliente/id
- GET /CodigoDeArea/{id}
- GET /CodigoDeArea/ (todos los codigos)
- GET /CodigoDeArea/Codigo/{codigo} (todos los codigos)
- GET /ContractTypes (todos los tipos de contrato)
- GET /Sesssion/{id}
- PUT /Session
- DELETE /Session/{id}
- GET /SessionDeleteAll

