# Viko_IagoMoreda

## Descripción

Este proyecto es una aplicación web que utiliza una arquitectura basada en contenedores Docker para separar los diferentes componentes del sistema. La aplicación está compuesta por un backend desarrollado en Symfony, un frontend creado con Vite y un sistema de scraping automatizado utilizando Selenium para obtener datos de Twitter. La estructura está diseñada para ser fácilmente desplegada en un entorno de producción gracias a la separación de los servicios en diferentes contenedores Docker.

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
   
   ![Captura de pantalla (15)](https://github.com/user-attachments/assets/bdf02fe0-3093-4771-8ed0-d422a9d576a4)
   
   ![Captura de pantalla (16)](https://github.com/user-attachments/assets/37060b5c-2903-43a3-a6c8-5e0ae0cdc292)

2. Creacion de la vista y los componentes de las graficas realizadas con  Chart.js y D3.js.
   
   ![Captura de pantalla (17)](https://github.com/user-attachments/assets/b30eb97f-5a68-4f17-8788-bfcc148ea765)
   
   ![Captura de pantalla (18)](https://github.com/user-attachments/assets/8372aea5-0407-4fe2-868f-b917ac4bccff)
   
   ![Captura de pantalla (20)](https://github.com/user-attachments/assets/ddffedf1-83e2-4b76-9e51-6f5f54e1c809)

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
   
   ![Captura de pantalla (14)](https://github.com/user-attachments/assets/8a9a5c4f-7b8e-4656-b166-e1ad51bd46ea)

### Contenedor de Selenium

**Descripción:** Contenedor que ejecuta Selenium con un navegador Firefox para automatizar la extracción de datos desde Twitter. Este contenedor está configurado para permitir la visualización en tiempo real de las acciones realizadas por Selenium a través de un puerto VNC.
**Funciones principales:**
1. Interactuar con el backend de Symfony para almacenar los tweets extraídos en la base de datos MySQL.
2. Permitir la visualización en tiempo real de las acciones de Selenium a través de VNC en `http://localhost:7900`.
