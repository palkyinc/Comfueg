# Registro de Cambios

Todos los cambios notables son registrados en este archivo.
___

<!-- # Notas del desarrollador
The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).
## [Unreleased]
1. haruncpi/laravel-user-activity
2. https://github.com/romainsimon/vue-simple-search-dropdown
3. https://laracasts.com/discuss/channels/code-review/custom-chartisanlaravel-charts-hooks-on-controller

### Secuencia de arctualización en server desarrollo:
1. Actualizar versión en el archivo constants.php
2. Realizar los cambios en Changelog.md actualizando la fecha de la versión.
3. Add y commit en el branch develop
4. Pasar a Master y luego realizar push de la versión con el tag si corresponde.
5. Volver develop.

### Secuencia de arctualización en server producción:
1. php artisan down
2. Si corresponde realizar un git restore.
3. realizar el git pull
4. Si corresponde realizar un docker build y editar el .yml con la nueva version de la imagen y correr docker-compose.
5. Realizar un migrate y un compose update.
6. php artisan up
___ -->
## [0.8.9] - 2023-05-06
### Changed
1.  Fix de reset mensual de los Contadores_mensuales
2.  Fix para planes
3.  Fix para contadores mensuales y charts.

## [0.8.8] - 2022-09-16
### Changed
1.  Se cambia pcq-limit para mejor manejo del arbol de colas en Mikrotik.
2.  Carpeta Packages para salvar el problema de la caida del repo de Chartisan v7.x. Se queda a la espera de v8.x
3.  Fixes en archivos de docker para contar con Docker Flow.
4.  Fix para pcq burst, rafagas.

## [0.8.7] - 2022-08-1
### Added
1.  Se agrega vista de Reportes
### Changed
1.  Cambios en adminCalles y adminBarrios
2.  Se agrega columna de Cometarios en listadoContratos. Aquí se muestra si contrato tiene velocidad a prueba.
___

## [0.8.6] - 2022-06-17
### Added
1. Programación de nueva Alta en Panel/gateway. Solo para tipo de contrato standard
2. Informe de contratoFull
3. Nuevo archivo calles.txt
___

## [0.8.5] - 2022-06-08
### Added
1. Programación de nueva Alta en Panel/gateway. Solo para tipo de contrato standard
2. Informe de contratoFull
3. Nuevo archivo calles.txt
### Changed
1. Mejoras en la vista de Clientes agregar y modificar.
2. se corrije problema en generar Archivo Semana.
___

## [0.8.4] - 2022-06-04
### Changed
1. corregido readDay error al capturar consumos.
2. corregido error al mostrar datos de consumos no completos.
___

## [0.8.3] - 2022-06-03
### Added
1. Se agrega PieChart de tipos de ticket generados.
### Changed
1. Cambio en SiteHasIncidenteController.php por error cuando equipo afectado es gateway.
___

## [0.8.2] - 2022-05-30
### Added
1. Resumen de ticket en vista de inicio/principal.
### Changed
1. removeClientBloqued, elimina todos los clientes bloqueados en mikrotik cada minuto.
2. Agregar potencia tx a Pruebas y la vista de Pruebas anteriores.
3. Se agrega tmr a issue titulos. Se completa getVencida en Issue. Se completa la vista de adminIssues con el estado de los tkt´s para seguimiento y calidad del servicio.
> - Agregar TMR´s en Titulos de Ticket.
4. Agregado 'creator' en Altas. Corregido en Admin Altas y programarAlta.
5. Agregado creator en Contratos. Resta instalator que se hará al momento de la instalación.
6. Se borra boton de agregar contratos.
7. Se cambia Horario de backup a las 20hs y subida a la nube a las 20.30hs
___

## [0.8.1] - 2022-05-24
### Changed
1. Arreglos en vista testContrato
2. Arreglos en Admin Altas, intentaba cargar router_id como false en el caso de tipo de Contrato 1.
___

## [0.8.0] - 2022-05-21
### Added
1. Altas admin terminado vistas.
### Changed
1. Metodo para realizar cambios en Clientes
___

## [0.7.13] - 2022-04-20
### Added
1. /listadoIssues, emite informe de todos los tickets abiertos en csv.
### Changed
1. Cambio en los archivos de docker para la restauración del sistema.
2. Cambio en archivo Config/backup agregando carpeta docker/aircontrol.
___

## [0.7.12] - 2022-03-04
### Added
1.	Se agrega vista para clientes.
___

## [0.7.11] - 2022-02-03
### Added
1.	Se agrega métodos para asignar IPs a los contratos de manera automática.
___

## [0.7.10] - 2022-01-05
### Added
1.	Se agrega gráfico de conteo mensual al modal de consumosCliente.
___

## [0.7.9] - 2022-01-04
### Changed
1.	Se arregla captura de contadores de Mikrotik para lo contratos.
2.	Modificaciones en archivos de DCP.
___

## [0.7.8] - 2021-12-27
### Added
1.	Backup manual y subida de archivos manual a cloud.
### Changed
1.	Se arregla error el traer vlans de un gateway afectadas a algún proveedor.
___

## [0.7.7] - 2021-12-16
### Changed
1.	Se agrega div_classifier para poder optimizar los mangles de mikrotik
2.	Se mejora vistas de proveedores, agregar, modificar y adminProveedores
3.	Se agrega renew de IP en antena de cliente al realizar un cambio de plan.
5.	Cambios varios en los archivos de DCP.
___

## [0.7.6] - 2021-12-06
### Added
1.	Se agrega restore de backup desde sistema/backup.
___

## [0.7.5] - 2021-12-02
### Added
1.	Comando restore de backup.
### Changed
1.	Arreglo en métodos de grabado de datos en paneles Ubiquiti.
___

## [0.7.4] - 2021-11-23
### Added
1.	Se agregó schedule para borrar archivos de Crons más antiguos de 7 días.
___

## [0.7.3] - 2021-11-22
### Added
1.	Se agrega tarea por schedule para sincronizar backup en server con Google Drive.
### Changed
1.	Correcciones en console/kernel.php y docker-compose.yml.
___

## [0.7.2] - 2021-11-21
### Added

1. [Se instala manejo de archivos zip desde laravel](https://github.com/zanysoft/laravel-zip).
2. [Se instala complemento para automatizar backup de la aplicación](https://spatie.be/docs/laravel-backup/v7/installation-and-setup).
3.	Se configura backup para DB y carpetas con archivos de usuarios.
___

## [0.7.1] - 2021-11-10
### Changed
1. 	Se realizan parches por cambio de servidor, nuevo servidor Alpine Linux.
2.	Se agrega test antena Cliente.
3.	Se realizan correcciones en mikrotik en per-connection-classifier pasado src address.
4.	Se pasa envio de email por caida de proveedores a 5 Min.
___

## [0.7.0] - 2021-09-14
### Added
1. 	Se agrega modulo Pedidos de asistencia técnica de Clientes.
___

## [0.6.16] - 2021-09-08
### Added
1. 	Se agrega conteo de datos.
2. 	Reset de contadores de los mikrotik el primero de mes.
___

## [0.6.15] - 2021-07-19
### Changed
1. 	Se resuelve problema con los classifiers al momento de modificar proveedores.
2.	Se corrije el problema de agragar un solo proveedor y con ip fija.
___

## [0.6.14] - 2021-07-18
### Added
1. 	Aparace alerta en vista principal cuando un proveedor esta offline.
2.	En vista "Administracion de Contratos":
> - Aparece celular de cliente al pararse sobre el nombre.  
> - Si se posee permiso de edicion de equipos, el nombre de equipo aparece con un hipervínculo que abrirá pestaña de esdicion de equipo.  
> - La columna "Barrio" se combio por "Ubicación" al pararse sobre el barrio aparecerá la direccion del contrato  
> - Si están cargadas las coordenadas de la ubicación del contrato, aparece icono sobre el cual se puede abrir pestaña que nos llega a google map con la ubicación.  
3.	En Deudas tecnicas:
> - Se agregan los siguientes: Prioridad, fecha tentiva y precedencia  
> - Se modifican las vistas de admin, agregar y modificar  
> - Se modifica tabla en mail semanal de duedas tecnicas pendientes  
### Changed
1. 	Se corrige problema en CronFunciones que provocaba no ver historial de navegación semanal.
___

## [0.6.13] - 2021-05-31
### Added
1. 	Agregado el campo de "límites" a la tabla Barrios.
2.	Se modificó ContratoCOntroller cuando hace update por error al borrar mac address en cambio de plan. Debía borrarse en cambio de panel.
3.	Cron de envio de mail de Caida de Proveedor. Chequea cada 1 minuto.
___

## [0.6.12] - 2021-05-31
### Added
1. 	Cron para envio de mail Resumen Deudas Tecnicas no finalizadas.
___

## [0.6.11] - 2021-05-31
### Added
1.	Se agrega Complemento de putty. PSCP.exe
2.	En vista de nuevo contrato agrega mac address al Panel de manera automática. [Referencia](https://ixnfo.com/en/ubiquiti-ssh-management.html)
3.	Vista adminContratos modificada para Dar de Baja contratos, agregar y quitar Mac Address automáticamente. Tambien en vista de editar contrato se agregan y quitan macs automatic.
___

## [0.6.10] - 2021-04-13
### Added
1.	Mas Frases motivacionales.
### Changed
1.	Corregido el error que se generaba al actualizar Proveedores en el routing Mark con Distancia 2.
2.	Se agrega IP y Barrio a la vista de Contratos.
___

## [0.6.9] - 2021-04-10
### Added
1.	Implementa archivo total consumido en el mes.
### Changed
1.	modifica Schedule para daily, le faltaba agregar el timezone.
2.	Modifica problema al actualizar y borrar proveedores.
3.	modifica problema al actualizar planes.
4.	modificado problema al actualizar contratos.
___

## [0.6.8] - 2021-04-06
### Added
1.	Implementa caja de rebusqueda en contratos.
2.	Implementa Cron para generar archivo semanal.
3.	Implemeta baja de Plan.
4.	Implementa vista semanal de consumos.
### Changed
1.	CORREGIDO. Al cambiar Gateway en plan falta verificar que no hay contratos con este plan.
2.	CORREGIDO. Cambiar en vista admin interfaces no parace el gateway seleccionado correctamente.
___

## [0.6.7] - 2021-03-31
### Added
1. Se agrega boton de visualizar datos de navegacion en Contratos para poder ver navegación del cliente.
___

## [0.6.6] - 2021-03-30
### Added
1. Vista de Historial de test panel.
2. se agrega cron para captura de datos de trafico de navegación de contratos. Se debe configurar tarea para correr readBytes.bat una vez por minuto en el servidor.
___

## [0.6.5] - 2021-03-29
### Changed
1.	Se corrige bug en Status Paneles que generaba errores al momento de grabar en base de datos.
___

## [0.6.4] - 2021-03-26
### Changed
1. 	Se arreglaron bugs en agregar y modificar proveedores.
2.	Se implemento Classifiers para proveedores con base de 5Mb.
___

## [0.6.3] - 2021-03-24
### Added
1.	Vista modificar contratos se agrega campo de coordenadas.
2.	Agregado updateCalles con archivo calles.txt.
### Changed
2.	Vista modificar contratos se restringe Equipos Cliente al propio o a los no asignados.
___

## [0.6.1] - 2021-03-23
### Added
1. Agregar y modificar Contratos.
### Changed
1.	(Corregido) Corregir en programacion de Proveedores no graba con Kb.
2.	(Corregido) Al crear un server Hotspot lo crea disabled.
3.	(Corregido). Undefined index: out-interface-list al agregar un sergundo proveedor(C:\inetpub\wwwroot\Comfueg\app\Custom\GatewayMikrotik.php:512)
4.	(Corregido) Error al crear Plan tree faltaba setear: packet-mark.
___

## [0.6.0] - 2021-03-22
### Added
1.	CRUD de plan en gateway Mikrotik. (Falta baja de plan).
2.	CRUD de Interfaces.
3.	CRUD de Proveedores.
4.	Se implementan funciones de CRUD de contratos hacia Mikrotik.
___

## [0.5.4] - 2021-03-03
### Added

1. Se instala [Laravel Charts](https://charts.erik.cat/).
___

### Changed
1.	Se corrige problemas en la carga de archivos adjuntos en una Deuda Tecnica Nueva.
2.	Cambiar Nodos Panel de Control por Nodo Status.
3.	Preparar vista sencilla de Status Paneles para operadores.
4.	Cambiar Sistema/Log que redirija a Inicio hasta que se desarrolle la vista.
5.	Cambiar a readonly Mac address en la vista de modificarEquipo
6.	Se agregan comentarios del equipo en la vista de Nodo.
7.	Se agrega cable fecha y cable tipo en Modificar y agregar Panel.
8.	se agrega columna de activo a sitiopara que no se muestre en la vista de Nodos.
___

## [0.5.3] - 2021-02-25
### Added
1. 	Copia datos de canal, clientes en cada pedido de http request al ubiquiti.
2.	Agrega datos de canal y clientes en panel en la vista de adminNodos.
3.	Graba datos del panel cada vez que se hace un http request al ubiquiti en tabla pruebas.
4.	Agrega al CRUD de Paneles los campos de usuario y contraseña, por seguridad no se hace desde equipos.
### Changed
1. 	Corrige AppServiceProvider por error al intentar recuperar contraseña por parte de un usuario.
___

## [0.5.2] - 2021-02-15
### Changed
1. 	Corrige problema en productos con cod_comfueg duplicado.
2.	Corrige mensajes al retornar de Activa/desactivar en adminEquipos.
3.	Corrige NodoController metodo showNodo por error al intentar mostrar un nodo que no tiene paneles cargados.
4.	Corrige AppServiceProvider implode falla con datos de tipo Objeto, se reescribe método.
___

## [0.5.1] - 2021-02-10
### Changed
1. 	Se utiliza url global desde constant.php para adminControlPanelNodos.js
2.	Se utiliza version de Vue.js desde constant.php para adminControlPanelNodos.js
3.	Se actualiza query para allPanels.
___

## [0.5.0] - 2021-02-09
### Added
1. 	Agregado query.log. Registra todo cambio en MySQL. No registra los querys de 'select'. App/Providers/AppServiceProvider.php. Deshabilitar para realizar las migrations.
2.	Agrega 'Sistema' en el menú pricipal. Se mueve alli desde las vistas de usuarios, Roles, Permisos, Grupos de mail, entidades.
3.	RouterOs Class.
4.	Vista Nodo Status con Ubiquiti Class modificada para que funcione con los equipos AC y Gen2. Se implementa vista con Vue.js
5.	Crud de Deuda Técnica completado.
___

## [0.4.0] - 2021-01-15
### Added
1.	Vista mostrarNodo agregar link al equipo desde el IP.
2.	Agregar frecuencia y cantidad de clientes a Paneles.
3.	Agregar tabla site_has_incidencia
4.	agregar tabla panel_has_barrios.
5.	Se agrega tablas Mail_groups y Mail_group_has_users
6.	Se crea el crud de barrios por panel.
7.	Se crea crud de Usuarios a Grupos de mail.
8.	Se crea CRUD de Incidente Globales. No se completo agregar archivos a Incidentes.
9.	Se crea CRUD de Entidades para has_file.
10.	Se agregó carga de archivos simultaneos a vistas de Sitio e Incidentes.
11.	Finalizada secciones de Incidencia Global, Nodos, Barrios Por panel.
### Changed
1.	MostrarNodos: luego de agregar un esquema o cobertura debe volver a mostrarNodos
2.	Problema de permisos en IIS_IURS al grabar en carpeta public
3.	Retorno de la vista cambiarFilePanel.
4.	Se cambio Bootstrap a version 5. Genera varios problemas. Se vuelve a version 4.5.
___

## [0.3.0] - 2020-12-01
### Added
1. Crear tabla, entity_has_file, para vicular archivos (fotos/pdfs) con paneles, sitios
### Changed
1. Agregar distancia Sitio anterior en sitios.
2. Agregar Altura en seccion Paneles.
3. Se cambia la vista de Paneles. Se retira columna de Cobertura, Se agrega distancia y boton de activar/desactivar con color de la fila.
### Removed
1. COlumna Cobertura de la tabla de Paneles.
___

## [0.2.0] - 2020-11-20
### Added
1. Carpeta "sql" renombrada a "extras".
2. Nuevo backup de la base de datos con las tablas eliminadas. -> slam20201115.sql
3. Instalar Laravel UI
4. Instalar [node.js](https://nodejs.org/en/download/) para poder correr los comandos NPM.
5. Se habilitó envios de mail.
> - Configuración con datos de cuenta de gmail en .env  
6. Backup en 'extras' de los archivos:
> - vendor/laravel/framework/src/illuminate/Auth/Passwords/CanResetPassword.php  
> - vendor/laravel/framework/src/illuminate/Auth/Notifications/ResetPassword.php  
7. Instalar Laravel Permission de Spatie
8. Se generan vistas de admin, editar, nuevo para Usuarios, Roles y Permisos.
9. Se generan vistas de agregar para Usuarios, Roles y Permisos.
10. Se generan vistas de:
> - Asginar permisos a rol.  
> - Asignar permiso a roles.  
> - Asignar rol a Usuario.  
11. Se adecuan vistas admin, agregar, modificar a permisos y roles.
### Removed
1. Base de Datos.
> - Tabla audits  
> - Tabla usuarios  
> - Tabla niveles  
> - Version con las tablas removidas -> slam20201115.sql  
2. Se borra Niveles del Menú Datos, del model, controller y todas las vistas.
___  

## [0.1.1] - 2020-11-12
### Added
> - MySQL backup in sql Folder.
___

## [0.1.0] - 2020-11-12
### Added
> - Sections add and modify of: Antena, Barrio, Calle, Ciudad, Codigos de Area, direccion, equipo, panel, productos, plan, site and cliente were completed.  
> - This CHANGELOG file to hopefully serve as an evolving example of a
  standardized open source project CHANGELOG.  
> - README now contains answers to common questions about CHANGELOGs  