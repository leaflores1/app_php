--README / Guía de Uso--

Este proyecto es una pequeña API en PHP con Doctrine ORM, empaquetada junto a MySQL para ejecutarse en contenedores Docker. Provee un endpoint POST / que permite registrar usuarios en la base de datos, usando name, email y password. Puedes extenderlo o probarlo siguiendo las instrucciones.

--Requisitos previos--

Docker instalado (versión 20.10 o superior recomendada).
Docker Compose instalado (versión 1.29 o superior recomendada).


--Estructura de Archivos Principales--

docker-compose.yml: Define los servicios (app y db), puertos, volúmenes, etc.
Dockerfile: Imagen base de PHP 8.3 con Apache + extensiones y Composer.
src/: Contiene el código fuente de dominio, entidades, repositorios, etc.
tests/: Directorio para pruebas unitarias y/o de integración.
update_schema.php y config/bootstrap_doctrine.php: Scripts para crear/actualizar el esquema de la base de datos usando Doctrine.
index.php: “Front controller” que maneja las solicitudes HTTP (GET y POST).

--Cómo Ejecutar el Proyecto--
1.Clona este repositorio:
    git clone https://github.com/tu-usuario/tu-repositorio.git
    cd tu-repositorio

2.Construye y levanta los contenedores (en segundo plano):
    docker-compose up -d --build

    Esto creará dos contenedores:
    app_php: contiene PHP, Apache, Composer, etc.
    mysql_php: contiene la base de datos MySQL 8.

3.Confirma que los contenedores se están ejecutando:
    docker-compose ps

    Deberías ver ambos servicios app_php y mysql_php en estado Up.

4.Inicializa o actualiza el esquema de la base de datos (opcional)
    El contenedor app ejecuta automáticamente php update_schema.php en el comando de arranque, pero si necesitas forzar la actualización:
    docker-compose exec app php update_schema.php

    Verás un mensaje: “¡Esquema actualizado correctamente!” si todo fue bien.


--Uso de la API--
Una vez levantados los contenedores:

El servicio de la aplicación (PHP + Apache) está disponible en http://localhost:8000.
MySQL está disponible en localhost:3307, usuario root, contraseña leandro1, base de datos app_php.

    1. GET (ver mensaje de bienvenida)
    Endpoint: GET /
    Ejemplo:
    curl http://localhost:8000

    Respuesta: 
    {
    "message": "Welcome to the API. Use POST to register a user with name, email, and password."
    }

    2. POST (crear un usuario)
    Endpoint: POST /
    Body (JSON):
    {
    "message": "Usuario registrado exitosamente",
    "user": {
        "name": "Juan Perez",
        "email": "juan@example.com",
        "id": "user_63fabb..."
    }
    }

    Error (HTTP 400) si faltan campos o si ya existe un usuario con ese email:
    {
    "error": "Ya existe un usuario con el email: juan@example.com"
    }

    o cualquier mensaje de validación que lance el dominio.


--Comandos Útiles--
Desde la carpeta raíz del proyecto puedes usar:

Levantar en segundo plano:
    docker-compose up -d --build

Ver logs en tiempo real:
    docker-compose logs -f

Acceder a la shell de la app (para ejecutar comandos dentro del contenedor):
    docker-compose exec app bash

Correr pruebas (si tienes PHPUnit configurado):
    docker-compose exec app vendor/bin/phpunit

Detener y eliminar contenedores (y su red asociada):
    docker-compose down

Conexión Manual a MySQL:
Si lo deseas, puedes conectarte manualmente al contenedor de MySQL:

    docker-compose exec db mysql -u root -p

Te pedirá la contraseña (leandro1 por defecto). También puedes conectar con MySQL Workbench o cualquier cliente externo usando:

    Host: 127.0.0.1
    Puerto: 3307
    Usuario: root
    Contraseña: leandro1
    Base de datos: app_php


--Ejecutar Pruebas--
Este proyecto utiliza PHPUnit para pruebas unitarias y de integración.

1. Ubicación de los tests
    Pruebas unitarias: Están en tests/Unit, verifican funcionalidades individuales de las clases.
    Pruebas de integración: Están en tests/Integration, verifican la interacción con la base de datos.
2. Cómo correr las pruebas
Asegúrate de que los contenedores estén corriendo:
    docker-compose up -d
Ejecuta las pruebas con el siguiente comando:
   docker-compose exec app vendor/bin/phpunit
3. Ejemplo de salida esperada
Si las pruebas pasan correctamente, verás algo como esto:

PHPUnit 11.5.10 by Sebastian Bergmann and contributors.

...                                                                 3 / 3 (100%)

Time: 00:01.383, Memory: 12.00 MB

OK (3 tests, 7 assertions)

Si hay errores, PHPUnit mostrará detalles del fallo, como por ejemplo:

1) Tests\Integration\DoctrineUserRepositoryTest::testSaveAndFindUser
Doctrine\DBAL\Exception\ConnectionException: An exception occurred in the driver: SQLSTATE[HY000] [2002] Connection refused

En ese caso, asegúrate de que la base de datos está corriendo (docker-compose restart db) y vuelve a intentar.


--Consideraciones Finales--

El Dockerfile ya habilita mod_rewrite y expone el puerto 80 interno, que mapeamos a localhost:8000 en tu máquina.
En caso de que necesites cambiar el puerto de la aplicación o el de MySQL, ajusta la sección ports: en el docker-compose.yml.
El archivo composer.json incluye todas las dependencias necesarias (Doctrine, etc.). El contenedor ejecuta composer install por ti.
Si tu equipo no tiene mucha RAM y Docker falla al construir la imagen, considera asignar más memoria a Docker.
¡Listo! Con esto, cualquier persona puede clonar tu repo, seguir estos pasos y tener la API corriendo sin problemas en Docker.

