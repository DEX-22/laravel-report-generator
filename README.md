# Laravel Report Generator

Este proyecto es una aplicación Laravel para la generación de reportes financieros personalizados a partir de datos de suscripciones, tarjetas de crédito, préstamos y otras deudas.

## Características
- Generación de reportes generales de deudas por rango de fechas.
- Procedimientos almacenados en la base de datos para consolidar información.
- Interfaz web para seleccionar fechas y descargar reportes.
- Uso de Blade, Tailwind CSS y Vite para el frontend.

## Instalación

1. Clona el repositorio:
   ```bash
   git clone <url-del-repo>
   cd laravel-report-generator
   ```
2. Instala las dependencias:
   ```bash
   composer install
   npm install && npm run build
   ```
3. Copia el archivo de entorno y configura tus variables:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Configura la base de datos en `.env` y ejecuta las migraciones:
   ```bash
   php artisan migrate
   ```
5. Inicia el servidor de desarrollo:
   ```bash
   php artisan serve
   ```
6. Inicia el servidor de colas (para jobs y notificaciones):
   ```bash
   php artisan queue:work
   ```
7. Inicia el servidor de WebSockets (Laravel Reverb):
   ```bash
   php artisan reverb:start
   ```

## Uso

- Accede a la aplicación en tu navegador en `http://localhost:8000`.
- Ingresa el rango de fechas y genera el reporte.
- Recibirás una notificación en tiempo real cuando el reporte esté listo, gracias a Laravel Reverb y WebSockets.

## Estructura principal
- `app/Http/Controllers/` — Controladores de la lógica de reportes.
- `resources/views/` — Vistas Blade para la interfaz de usuario.
- `database/migrations/` — Migraciones y procedimientos almacenados.
- `routes/web.php` — Rutas web de la aplicación.


