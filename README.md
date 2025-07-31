# Multi-Groceries Backend

## Descripción

Este proyecto es un API RESTful construida con Laravel 12 que sirve como backend para una aplicación de seguimiento y comparación de precios de comestibles. Permite a los usuarios registrar productos, tiendas y sus compras, mientras que ofrece funcionalidades clave para analizar el historial de precios y comparar los costos de un producto específico en diferentes tiendas.

## Características Principales

*   **Autenticación Segura:** Utiliza Laravel Sanctum para la autenticación basada en tokens.
*   **Gestión de Productos (CRUD):** Endpoints para crear, leer, actualizar y eliminar productos.
*   **Gestión de Tiendas (CRUD):** Endpoints para gestionar las tiendas donde se realizan las compras.
*   **Registro de Compras:** Capacidad para registrar compras detalladas, incluyendo los productos y precios en un momento dado.
*   **Historial de Precios:** Endpoint para consultar la evolución del precio de un producto a lo largo del tiempo.
*   **Comparación de Precios:** Endpoint para comparar el precio actual de un producto en todas las tiendas registradas.

## Requisitos Previos

*   PHP 8.2 o superior
*   Composer
*   Una base de datos compatible con Laravel (SQLite, MySQL, PostgreSQL, etc.)

## Instalación

1.  **Clonar el repositorio:**
    ```bash
    git clone https://github.com/tu-usuario/multi-groceries-backend.git
    cd multi-groceries-backend
    ```

2.  **Instalar dependencias de PHP:**
    ```bash
    composer install
    ```

3.  **Crear el archivo de entorno:**
    El proyecto está configurado para copiar `.env.example` a `.env` automáticamente después de la instalación. Si no, puedes hacerlo manualmente:
    ```bash
    cp .env.example .env
    ```

4.  **Generar la clave de la aplicación:**
    ```bash
    php artisan key:generate
    ```

5.  **Configurar la base de datos:**
    Abre el archivo `.env` y configura las variables de tu base de datos (p. ej., `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, etc.). Por defecto, está configurado para usar SQLite.

6.  **Ejecutar las migraciones:**
    Esto creará las tablas necesarias en tu base de datos.
    ```bash
    php artisan migrate
    ```
    *(Opcional: Si tienes seeders para poblar la base de datos, puedes correr `php artisan migrate --seed`)*

## Uso

Para iniciar el servidor de la API, ejecuta el siguiente comando:

```bash
php artisan serve
```

El API estará disponible en `http://127.0.0.1:8000`.

## Ejecutar Pruebas

Para ejecutar el conjunto de pruebas automatizadas, utiliza el siguiente comando:

```bash
php artisan test
```

## Endpoints del API

Todos los endpoints están prefijados con `/api`.

### Autenticación

| Método | URI | Descripción |
| :--- | :--- | :--- |
| `POST` | `/register` | Registra un nuevo usuario. |
| `POST` | `/login` | Inicia sesión y obtiene un token de autenticación. |
| `POST` | `/logout` | Cierra la sesión del usuario (requiere autenticación). |
| `GET` | `/user` | Obtiene la información del usuario autenticado. |

### Recursos Protegidos

Los siguientes endpoints requieren un token de autenticación válido en la cabecera `Authorization: Bearer <token>`.

#### Productos
*   `GET /products`
*   `POST /products`
*   `GET /products/{product}`
*   `PUT/PATCH /products/{product}`
*   `DELETE /products/{product}`

#### Tiendas
*   `GET /stores`
*   `POST /stores`
*   `GET /stores/{store}`
*   `PUT/PATCH /stores/{store}`
*   `DELETE /stores/{store}`

#### Registros de Compras
*   `GET /shopping-records`
*   `POST /shopping-records`
*   `GET /shopping-records/{shopping_record}`
*   `PUT/PATCH /shopping-records/{shopping_record}`
*   `DELETE /shopping-records/{shopping_record}`

#### Funcionalidades Especiales

| Método | URI | Descripción |
| :--- | :--- | :--- |
| `GET` | `/products/{product}/price-history` | Devuelve el historial de precios para un producto específico. |
| `GET` | `/products/{product}/store-comparison` | Compara el último precio registrado de un producto en todas las tiendas. |