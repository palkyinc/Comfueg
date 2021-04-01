# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
1. haruncpi/laravel-user-activity

## [0.6.x] - 2021-XX-XX
### Added
1.	Implemetar baja de Plan
### Changed
1.	Cambiar en vista admin interfaces no parace el gateway seleccionado correctamente.
2.	Undefined variable: respuesta al borrar una vlan
3.	Al cambiar Gateway en plan falta verificar que no hay contratos con este plan.
### Removed

## [0.6.7] - 2021-03-31
### Added
1. Se agrega boton de visualizar datos de navegacion en Contratos para poder ver navegación del cliente.

## [0.6.6] - 2021-03-30
### Added
1. Vista de Historial de test panel.
2. se agrega cron para captura de datos de trafico de navegación de contratos. Se debe configurar tarea para correr readBytes.bat una vez por minuto en el servidor.

## [0.6.5] - 2021-03-29
### Changed
1.	Se corrige bug en Status Paneles que generaba errores al momento de grabar en base de datos.

## [0.6.4] - 2021-03-26
### Changed
1. 	Se arreglaron bugs en agregar y modificar proveedores.
2.	Se implemento Classifiers para proveedores con base de 5Mb.

## [0.6.3] - 2021-03-24
### Added
1.	Vista modificar contratos se agrega campo de coordenadas.
2.	Agregado updateCalles con archivo calles.txt.
### Changed
2.	Vista modificar contratos se restringe Equipos Cliente al propio o a los no asignados.

## [0.6.1] - 2021-03-23
### Added
1. Agregar y modificar Contratos.
### Changed
1.	(Corregido) Corregir en programacion de Proveedores no graba con Kb.
2.	(Corregido) Al crear un server Hotspot lo crea disabled.
3.	(Corregido). Undefined index: out-interface-list al agregar un sergundo proveedor(C:\inetpub\wwwroot\Comfueg\app\Custom\GatewayMikrotik.php:512)
4.	(Corregido) Error al crear Plan tree faltaba setear: packet-mark.


## [0.6.0] - 2021-03-22
### Added
1.	CRUD de plan en gateway Mikrotik. (Falta baja de plan).
2.	CRUD de Interfaces.
3.	CRUD de Proveedores.
4.	Se implementan funciones de CRUD de contratos hacia Mikrotik.


## [0.5.4] - 2021-03-03
### Added
1.	Se instala Laravel Charts. https://charts.erik.cat/

### Changed
1.	Se corrige problemas en la carga de archivos adjuntos en una Deuda Tecnica Nueva.
2.	Cambiar Nodos Panel de Control por Nodo Status.
3.	Preparar vista sencilla de Status Paneles para operadores.
4.	Cambiar Sistema/Log que redirija a Inicio hasta que se desarrolle la vista.
5.	Cambiar a readonly Mac address en la vista de modificarEquipo
6.	Se agregan comentarios del equipo en la vista de Nodo.
7.	Se agrega cable fecha y cable tipo en Modificar y agregar Panel.
8.	se agrega columna de activo a sitiopara que no se muestre en la vista de Nodos.

## [0.5.3] - 2021-02-25
### Added
1. 	Copia datos de canal, clientes en cada pedido de http request al ubiquiti.
2.	Agrega datos de canal y clientes en panel en la vista de adminNodos.
3.	Graba datos del panel cada vez que se hace un http request al ubiquiti en tabla pruebas.
4.	Agrega al CRUD de Paneles los campos de usuario y contraseña, por seguridad no se hace desde equipos.

### Changed
1. 	Corrige AppServiceProvider por error al intentar recuperar contraseña por parte de un usuario.

## [0.5.2] - 2021-02-15
### Changed
1. 	Corrige problema en productos con cod_comfueg duplicado.
2.	Corrige mensajes al retornar de Activa/desactivar en adminEquipos.
3.	Corrige NodoController metodo showNodo por error al intentar mostrar un nodo que no tiene paneles cargados.
4.	Corrige AppServiceProvider implode falla con datos de tipo Objeto, se reescribe método.

## [0.5.1] - 2021-02-10
### Changed
1. 	Se utiliza url global desde constant.php para adminControlPanelNodos.js
2.	Se utiliza version de Vue.js desde constant.php para adminControlPanelNodos.js
3.	Se actualiza query para allPanels.

## [0.5.0] - 2021-02-09
### Added
1. 	Agregado query.log. Registra todo cambio en MySQL. No registra los querys de 'select'. App/Providers/AppServiceProvider.php. Deshabilitar para realizar las migrations.
2.	Agrega 'Sistema' en el menú pricipal. Se mueve alli desde las vistas de usuarios, Roles, Permisos, Grupos de mail, entidades.
3.	RouterOs Class.
4.	Vista Nodo Status con Ubiquiti Class modificada para que funcione con los equipos AC y Gen2. Se implementa vista con Vue.js
5.	Crud de Deuda Técnica completado.

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

## [0.3.0] - 2020-12-01

### Added
1. Crear tabla, entity_has_file, para vicular archivos (fotos/pdfs) con paneles, sitios

### Changed
1. Agregar distancia Sitio anterior en sitios.
2. Agregar Altura en seccion Paneles.
3. Se cambia la vista de Paneles. Se retira columna de Cobertura, Se agrega distancia y boton de activar/desactivar con color de la fila.

### Removed
1. COlumna Cobertura de la tabla de Paneles.

## [0.2.0] - 2020-11-20
### Added
1. Carpeta "sql" renombrada a "extras".
2. Nuevo backup de la base de datos con las tablas eliminadas. -> slam20201115.sql
3. Instalar Laravel UI
4. Instalar node.js -> https://nodejs.org/en/download/ para poder correr los comandos NPM.
5. Se habilitó envios de mail.
	- Configuración con datos de cuenta de gmail en .env
6. Backup en 'extras' de los archivos:
	- vendor/laravel/framework/src/illuminate/Auth/Passwords/CanResetPassword.php
	- vendor/laravel/framework/src/illuminate/Auth/Notifications/ResetPassword.php
7. Instalar Laravel Permission de Spatie
8. Se generan vistas de admin, editar, nuevo para Usuarios, Roles y Permisos.
9. Se generan vistas de agregar para Usuarios, Roles y Permisos.
10. Se generan vistas de:
	- Asginar permisos a rol.
	- Asignar permiso a roles.
	- Asignar rol a Usuario.
11. Se adecuan vistas admin, agregar, modificar a permisos y roles.

### Removed
1. Base de Datos.
  - Tabla audits
  - Tabla usuarios
  - Tabla niveles
  - Version con las tablas removidas -> slam20201115.sql
2. Se borra Niveles del Menú Datos, del model, controller y todas las vistas.
  

## [0.1.1] - 2020-11-12
### Added
- MySQL backup in sql Folder.


## [0.1.0] - 2020-11-12
### Added
- Sections add and modify of: Antena, Barrio, Calle, Ciudad, Codigos de Area, direccion, equipo, panel, productos, plan, site and cliente were completed.
- This CHANGELOG file to hopefully serve as an evolving example of a
  standardized open source project CHANGELOG.
- README now contains answers to common questions about CHANGELOGs

[Unreleased]:
[0.1.0]: https://github.com/palkyinc/Comfueg/releases/tag/0.1.0