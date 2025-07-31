# TODO: Refactor a Catálogos Parametrizados y Jerárquicos

Este documento resume los pasos para refactorizar el sistema y utilizar una tabla de catálogos centralizada, flexible y jerárquica para gestionar parámetros como categorías de productos, unidades de medida y tipos de tiendas.

## 1. Crear la Tabla de Catálogos

El objetivo es tener una única tabla (`catalogs`) para todos los parámetros, con capacidad de anidar elementos (crear árboles).

- [ ] **Crear el Modelo y la Migración:**
  ```bash
  php artisan make:model Catalog -m
  ```

- [ ] **Definir el Esquema de la Tabla `catalogs`:**
  Modificar el archivo de migración generado para incluir una auto-referencia (`parent_id`) que permita la jerarquía.

  ```php
  // database/migrations/xxxx_xx_xx_xxxxxx_create_catalogs_table.php
  Schema::create('catalogs', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('parent_id')->nullable(); // Para la estructura de árbol
      $table->string('type'); // Ej: 'product_category', 'unit_of_measure', 'store_category'
      $table->string('value'); // Ej: 'Lácteos', 'Kilogramo', 'Supermercado'
      $table->json('details')->nullable(); // Para datos extra como abreviaturas
      $table->timestamps();

      $table->index('type');
      $table->foreign('parent_id')->references('id')->on('catalogs')->onDelete('cascade');
  });
  ```

## 2. Actualizar Modelos Existentes

Modificar las tablas `products` y `stores` para que usen la nueva tabla de catálogos en lugar de campos de texto.

- [ ] **Crear Migración para Modificar Tablas:**
  ```bash
  php artisan make:migration AddCatalogFieldsToModels
  ```

- [ ] **Añadir las Claves Foráneas en la Migración:**
  ```php
  // database/migrations/xxxx_xx_xx_xxxxxx_add_catalog_fields_to_models.php
  public function up(): void
  {
      Schema::table('products', function (Blueprint $table) {
          $table->unsignedBigInteger('category_id')->nullable()->after('id');
          $table->unsignedBigInteger('unit_of_measure_id')->nullable()->after('category_id');
          // Nota: No se usa constrained() directamente. La lógica se hará en el modelo.
      });

      Schema::table('stores', function (Blueprint $table) {
          $table->unsignedBigInteger('category_id')->nullable()->after('id');
      });
  }
  ```
- [ ] **Ejecutar las migraciones:**
  ```bash
  php artisan migrate
  ```

## 3. Configurar los Modelos Eloquent

Definir las relaciones en los modelos para que Laravel entienda la nueva estructura.

- [ ] **Configurar `app/Models/Catalog.php`:**
  Añadir las relaciones para la estructura de árbol.
  ```php
  class Catalog extends Model
  {
      // ...
      public function parent() {
          return $this->belongsTo(Catalog::class, 'parent_id');
      }

      public function children() {
          return $this->hasMany(Catalog::class, 'parent_id');
      }

      public function recursiveChildren() {
          return $this->children()->with('recursiveChildren');
      }
  }
  ```

- [ ] **Configurar `app/Models/Product.php` y `app/Models/Store.php`:**
  Añadir las relaciones `belongsTo` filtrando por el `type` correcto.
  ```php
  // En Product.php
  public function category() {
      return $this->belongsTo(Catalog::class, 'category_id')->where('type', 'product_category');
  }
  public function unitOfMeasure() {
      return $this->belongsTo(Catalog::class, 'unit_of_measure_id')->where('type', 'unit_of_measure');
  }

  // En Store.php
  public function category() {
      return $this->belongsTo(Catalog::class, 'category_id')->where('type', 'store_category');
  }
  ```

## 4. Crear el Endpoint de la API

Exponer los catálogos a través de un endpoint que devuelva la estructura jerárquica.

- [ ] **Crear el Controlador:**
  ```bash
  php artisan make:controller Api/CatalogController
  ```

- [ ] **Definir la Ruta en `routes/api.php`:**
  ```php
  // GET /api/catalogs?type=product_category
  Route::get('/catalogs', [Api\CatalogController::class, 'index']);
  ```

- [ ] **Implementar la Lógica en `CatalogController`:**
  El controlador debe devolver un árbol de elementos filtrado por tipo.
  ```php
  public function index(Request $request)
  {
      $request->validate(['type' => 'required|string']);

      $catalogs = Catalog::where('type', $request->type)
                          ->whereNull('parent_id') // Obtener solo los elementos raíz
                          ->with('recursiveChildren') // Cargar todos los descendientes
                          ->get();

      return $catalogs;
  }
  ```

## 5. Pasos Finales

- [ ] **Poblar Datos (Seeding):** Crear un `CatalogSeeder` para llenar la tabla `catalogs` con datos iniciales (categorías, unidades, etc.).
- [ ] **Actualizar Lógica de Negocio:** Modificar los controladores (`ProductController`, `StoreController`) para que al crear o actualizar un recurso, se usen los nuevos `category_id`, etc., validando que el ID exista y sea del tipo correcto.
- [ ] **Limpieza:** Eliminar las columnas de texto antiguas de las tablas (`products`, `stores`) una vez que los datos hayan sido migrados a la nueva estructura.
