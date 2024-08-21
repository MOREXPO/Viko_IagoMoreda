# Viko_IagoMoreda

## Descripción

Este proyecto es una aplicación web que utiliza una arquitectura basada en contenedores Docker para separar los diferentes componentes del sistema. La aplicación está compuesta por un backend desarrollado en Symfony, un frontend creado con Vite y un sistema de scraping automatizado utilizando Selenium para obtener datos de Twitter. La estructura está diseñada para ser fácilmente desplegada en un entorno de producción gracias a la separación de los servicios en diferentes contenedores Docker.

Ademas se oculto el archivo .env, el cual es necesario para configurar nuestra app, con la siguiente estructura:
```
COMPOSE_PROJECT_NAME=
MYSQL_ROOT_PASSWORD=
MYSQL_DATABASE=
MYSQL_USER=
MYSQL_PASSWORD=

TIMEZONE=
APP_ENV=

USERNAME_TWITTER=
EMAIL_TWITTER=
PASSWORD_TWITTER=

CORS_ALLOW_ORIGIN=
VITE_HOST=
```
y el archivo .env.nginx.local, con la siguiente estructura:
```
NGINX_BACKEND_DOMAIN=''
```
## Estructura del Proyecto
El proyecto está organizado en los siguientes contenedores Docker:

### Backend (Symfony)

**Descripción:** Contenedor donde reside la aplicación backend desarrollada con Symfony. Aquí se manejan las operaciones del servidor, como la gestión de datos y la ejecución de comandos.

**Funciones principales:**
1. Ejecutar comandos de Symfony.  
1.2. Comando de Scraping  
En el contenedor del backend se creó un comando personalizado de Symfony que utiliza Selenium para realizar el scraping en Twitter y utiliza un analizador de sentimientos para ponderar cada tweet: `docker compose exec php bin/console app:fetch-tweets`
2. Conectar con la base de datos MySQL y meter los tweets escrapeados en ella.
3. Exponer la API mediante ApiPlatform.
### Frontend (Vite)

**Descripción:** Contenedor donde se encuentra el frontend de la aplicación, desarrollado con Vite. Durante el desarrollo, utiliza su propio servidor de desarrollo integrado. En producción, se sirve a través de un servidor Nginx.

**Funciones principales:**
1. Creacion de la vista y el componente de la tabla realizado con el datatable de vuetify.
   
   ![Captura de pantalla (15)](https://github.com/user-attachments/assets/0199b003-8950-4788-b192-5c89394422ac)

   
   ![Captura de pantalla (16)](https://github.com/user-attachments/assets/228be8f3-b289-4243-ad7d-f8844216af16)


2. Creacion de la vista y los componentes de las graficas realizadas con  Chart.js y D3.js.
   
   ![Captura de pantalla (22)](https://github.com/user-attachments/assets/abe72a87-e449-4f49-a8e7-558ce22bae2b)

   
   ![Captura de pantalla (18)](https://github.com/user-attachments/assets/c97ecbf5-5531-45ad-b3ce-f14bc026ea4d)

   
   ![Captura de pantalla (20)](https://github.com/user-attachments/assets/85a33ead-c75b-447b-9e6c-58a3f8a1da45)


### Servidor Nginx para Backend

**Descripción:** Servidor Nginx que actúa como un proxy inverso para el backend.
**Funciones principales:**
1. Servir la API del backend.

### Servidor Nginx para Frontend para producción

**Descripción:** Servidor Nginx que actúa como un proxy inverso para el frontend cuando se despliega en producción.
**Funciones principales:**
1. Servir el frontend en producción.

### Contenedor de MySQL

**Descripción:** Contenedor que ejecuta MySQL, utilizado como la base de datos principal de la aplicación. Almacena todos los datos, incluyendo los tweets extraídos por Selenium.
**Funciones principales:**
1. Facilitar la gestión y administración de la base de datos mediante el mapeo de puertos para permitir el acceso desde el exterior del contenedor.
   
   ![Captura de pantalla (14)](https://github.com/user-attachments/assets/cd67ee8e-f007-44aa-a558-d625879d5890)


### Contenedor de Selenium

**Descripción:** Contenedor que ejecuta Selenium con un navegador Firefox para automatizar la extracción de datos desde Twitter. Este contenedor está configurado para permitir la visualización en tiempo real de las acciones realizadas por Selenium a través de un puerto VNC.
**Funciones principales:**
1. Interactuar con el backend de Symfony para almacenar los tweets extraídos en la base de datos MySQL.
2. Permitir la visualización en tiempo real de las acciones de Selenium a través de VNC en `http://localhost:7900`.
