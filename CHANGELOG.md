# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 20XX-XX-XX
### Added
### Changed
### Removed

## [0.2.0] - 20XX-XX-XX
### Added
1. Carpeta sql renombrada a extras.
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
11. Se adecuan vistas adminXXXXX a permisos y roles.


### Changed
	
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
What is a changelog?