# EVENTOS LARAVEL 8 Y GOOGGLE CALENDAR

API gestion de eventos en Laravel 8.* sincronizado con API GOOGLE CALENDAR

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

*  Crear Proyecto  en https://developers.google.com/google-apps/calendar 
*  Crear Credenciales para el acceso a la API DE GOOGLE CALENDAR, Crear cliente OAuth, Configurar la pantalla de consentimiento. y habilitar la API GOOGLE CALENDAR
*  Aplicación de tipo web, al terminar descargar las credenciales formato JSON y copiarlo en la carpeta publica del proyecto laravel.
*  Copie el nombre del archivo y pegue en gCalendarController.php en app/Http/controller, linea 24 $client->setAuthConfig('client_secret.json');
*  En ambiente de pruebas localhost debe estar utilizando SSL encryption.
*  Editar archivo host del sistema operativo sudo nano /private/etc/hosts, en el caso de Mac 
         127.0.0.1         local.calendar.com     
* Crear virtual HOST en Apache con SSL HTTPS 

### Prerequisites

Laravel 8.*
PHP 7.4

### Installing

* Instalar Librería Google Client, Abrir terminal en el directorio del proyecto.

composer require google/apiclient:^2.0

composer install 

* Configurar el archivo .env.example renombrar a .env y confifurar la base de datos

* DB_CONNECTION=mysql
* DB_HOST=127.0.0.1
* DB_PORT=3306
* DB_DATABASE=events
* DB_USERNAME=root
* DB_PASSWORD=

* Abrir terminal en el directorio del proyecto. para ejecutar las migraciones a la base de datos anteriormente configurada

php artisan migrate 

## Running the tests

* Revisar el archivo routes/api.php 

* Ver las rutas con el comando
* Abrir terminal en el directorio del proyecto, ver las rutas del proyecto con sus caracteristicas

php artisan route:list 




