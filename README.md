
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
1.  Crear la carpeta donde descargaremos el proyecto:
    a.  sudo mkdir /media/proyectos
    b.  cd /media/proyectos
    c.  sudo apk add git
    d.  git clone https://github.com/palkyinc/Comfueg.git

## Habilitar ssh y crear usuario para acceso.
1.  Ingresar con root y contraseña seteada en el punto 12 anterior.
2.  Escribir: vi /etc/ssh/sshd_config
3.  editar el archivo de la sgiuiente manera:
    a.  presionar i
    b.  buscar y descomentar la linea que contiene: UseDNS no
    c.  Linea: #Port22 => editarla a. Port 2233
    d. Salir y guardar con la siguiente secuencia: ESC : w q enter
    d. En caso quere salir sin guardar, usar la siguiente secuencia: ESC : q ! enter
4.  Probar conexion utilizando Putty al 10.10.0.245 puerto 2233. Aceptar el nuevo host key.
5.  Agregar un nuevo usuario: adduser -g "Usuario Soporte" soporte. Setear una nueva contraseña. Registrarla y mantenerla segura.
6.  Agregamos permisos sudo al usuario soporte:
    a. apk add sudo
    b. echo '%wheel ALL=(ALL) ALL' > /etc/sudoers.d/wheel
    c. adduser soporte wheel
7.  Comporbacion de que funcionan los permisos sudo:
    a.  iniciar conexion con Putty (IP 10.10.0.245 puerto 2233)
    b.  login con las credenciales de soporte
    c.  corre el siguiente comando: apk update
    d.  Solicitará que se ingrese la contraseña sudo que la misma de soporte.
    e.  Si corre sin ningun error de sudo que es que todo está OK.
Conclusiones:
1.  A partir de ahora todo se puede trabajar a traves de PuTTY. No usar mas la consola con teclado y monitor.
2.  El usuario root no tiene permisos para conectarse por PuTTy, solo por consola. hay que usar el usuario soporte.




## Habilitar Firewall
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

