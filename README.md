# EcommerceV2 - Tienda de Muebles con Microservicios

Proyecto Laravel compuesto por tres servicios independientes que se comunican entre si via API REST.

## Estructura del proyecto

```
EcomerceV2/
   auth_api/       -> Gestiona usuarios, login y tokens (puerto 8000)
   forniture_api/  -> Gestiona el catalogo de muebles y categorias (puerto 8001)
   main_api/       -> Aplicacion principal con vistas, carrito y pedidos (puerto 8002)
```

---

## Requisitos previos

- PHP 8.2 o superior
- Composer
- SQLite (incluido en PHP por defecto)

---

## Primer arranque (solo la primera vez)

Hay que configurar cada proyecto por separado. Abre tres terminales y ejecuta lo siguiente en cada una.

### 1. auth_api

```bash
cd auth_api
composer install
cp .env.example .env      # si no existe ya el .env
php artisan key:generate
php artisan migrate --seed
php artisan serve --port=8000
```

### 2. forniture_api

```bash
cd forniture_api
composer install
cp .env.example .env      # si no existe ya el .env
php artisan key:generate
php artisan migrate --seed
php artisan serve --port=8001
```

### 3. main_api

```bash
cd main_api
composer install
cp .env.example .env      # si no existe ya el .env
php artisan key:generate
php artisan migrate
php artisan serve --port=8002
```

---

## Arranque normal (dias siguientes)

Solo necesitas ejecutar `php artisan serve` en cada carpeta con su puerto correspondiente.

**Terminal 1:**
```bash
cd auth_api
php artisan serve --port=8000
```

**Terminal 2:**
```bash
cd forniture_api
php artisan serve --port=8001
```

**Terminal 3:**
```bash
cd main_api
php artisan serve --port=8002
```

La aplicacion principal es accesible en: http://localhost:8002

---

## URLs de cada servicio

| Servicio       | URL base                        | Descripcion                        |
|----------------|---------------------------------|------------------------------------|
| auth_api       | http://localhost:8000/api       | Login, registro y validacion token |
| forniture_api  | http://localhost:8001/api       | Catalogo de muebles y categorias   |
| main_api       | http://localhost:8002           | Tienda web (vistas y carrito)      |

---

## Usuarios de prueba

Los seeders crean los siguientes usuarios por defecto:

| Rol      | Email                   | Password  |
|----------|-------------------------|-----------|
| Admin    | admin@example.com       | password  |
| Gestor   | gestor@example.com      | password  |
| Cliente  | cliente@example.com     | password  |

---

## Notas importantes

- Los tres servicios deben estar ejecutandose a la vez para que la aplicacion funcione correctamente.
- Las bases de datos son SQLite y se crean automaticamente al ejecutar `php artisan migrate`.
- El archivo `.env` de cada proyecto ya tiene configuradas las URLs de los otros servicios, no es necesario modificarlas si se usan los puertos por defecto.
- Las imagenes de los muebles se almacenan localmente en `main_api/public/img/muebles/`.
