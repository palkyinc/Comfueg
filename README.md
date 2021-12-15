
## Instalacion del proyecto - Requisitos
-Computadora/servidor
-Alpine Linux instalable. CD, DVD o pendrive. https://dl-cdn.alpinelinux.org/alpine/v3.12/releases/x86_64/alpine-extended-3.12.0-x86_64.iso
-Conexión a internet
-Putty instalado en PC de Administración
-Cargar mac address del nuevo servidor en los Mikrotiks.


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
20. How would you like use it?: sys. NOTA: si se realizan preguntas "Proceed anyway?" responder con "y" y "enter".
21. retirar el disco/pendrive de instalación. Tipear "reboot" y "enter"

## Descargar proyecto
22.  Ingresar con root y contraseña seteada en el punto 12 anterior.
23.  Crear la carpeta donde descargaremos el proyecto:
    a.  mkdir /media/proyectos
    b.  cd /media/proyectos
    c.  apk add git
    d.  git clone https://github.com/palkyinc/Comfueg.git

## Habilitar ssh y crear usuario para acceso.
24. cd Comfueg/docker
25. sh install-ssh.sh
    a.  ATENCION: Escribir para el usuario soporte. Mantenerga segura y a resguardo.
26. Comporbacion de que funcionan los permisos sudo:
    a.  iniciar conexion con Putty (IP 10.10.0.245 puerto 2233)
    b.  login con las credenciales de soporte
    c.  corre el siguiente comando: sudo apk update
    d.  Solicitará que se ingrese la contraseña sudo que la misma de soporte.
    e.  Si corre sin ningun error de sudo que es que todo está OK.
## Conclusiones:
    a.  A partir de ahora todo se puede trabajar a traves de PuTTY. No usar mas la consola con teclado y monitor. Ventaja adicional, se puede hacer copia y pega en la consola PuTTy. Para pegar en Putty se debe utilizar "shift" + "INS".
    b.  El usuario root no tiene permisos para conectarse por PuTTy, solo por consola. hay que usar el usuario soporte.

## Habilitar Firewall
27. sudo sh /media/proyectos/Comfueg/docker/install-awall.sh
    NOTA: Presionar ENTER cuando solicite "Press RETURN" para la configuracion inicial.
    NOTA2: al finalizar el script reinicia automaticamente el server. Confirmar mediante un ping.
28. Luego del reboot loguearse segun punto 26-a y 26-b. 

## Crear las imagenes y contenedores de Docker
29. cd /media/proyectos/Comfueg/docker
30. sudo sh install-dockerfile.sh
    NOTA: tarda bastante dependiendo de la conexion de internet.

## Inicializar Base de datos.
31. sudo sh mysql.sh
        Atención: Al observar la linea: "ready for connections. Version: '8.0.25'  socket: '/var/run/mysqld/mysqld.sock'  port: 3306  MySQL Community Server - GPL." Continuar con el siguiente punto.
32. Abrir otra ventana con Putty como en el punto XX
33. sudo sh mysql2.sh
34. exit en la segunda ventana

## Descargar backup y restaurar
35. sudo docker-compose up -d
36. sudo docker exec -it slam-php-apache sh
37. cd app
38. composer update
39. php
    

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

