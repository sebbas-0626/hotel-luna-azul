# Documentación del Proyecto - Hotel Luna Azul

## 1. Descripción general

Hotel Luna Azul es un sistema web para la gestión integral de un hotel. Permite administrar huéspedes, habitaciones y reservas, evitando dobles asignaciones y centralizando la información. El sistema está construido en PHP bajo el patrón MVC, usa MySQL como base de datos y Eloquent ORM para el acceso a datos. El frontend es una SPA ligera con HTML, CSS y JavaScript que consume una API REST.

## 2. Objetivos del sistema

- Digitalizar el registro de huéspedes.
- Controlar la disponibilidad de habitaciones.
- Gestionar reservas con validación de fechas.
- Evitar doble asignación de una misma habitación.
- Proveer un panel de control con métricas básicas.

## 3. Stack tecnológico

- Lenguaje backend: PHP 8.x
- Base de datos: MySQL 8.x
- ORM: Illuminate/Database (Eloquent) standalone
- Frontend: HTML5, CSS3, JavaScript (Fetch API)
- Servidor web: PHP built-in server o Apache (XAMPP)
- Gestor de dependencias: Composer

## 4. Arquitectura MVC

- `app/Core`: clases base del framework (Router, Controller, Request, Response).
- `app/Models`: modelos Eloquent que representan las tablas (Usuario, Huesped, Habitacion, Reserva).
- `app/Controllers`: controladores que procesan las peticiones y devuelven JSON.
- `routes/web.php`: registro de rutas públicas y de API.
- `views/`: vistas HTML/PHP renderizadas al cliente.
- `public/`: punto de entrada (`index.php`), recursos estáticos (CSS/JS).

Flujo de una petición:
1. El navegador solicita una URL.
2. `public/index.php` (front controller) carga el Router.
3. El Router despacha al Controller correspondiente.
4. El Controller usa el Model para consultar/actualizar la BD.
5. Se retorna JSON (API) o se renderiza una vista.

## 5. Estructura de carpetas

```
/hotel-luna-azul
├── app/
│   ├── Controllers/
│   ├── Core/
│   └── Models/
├── database/
│   ├── schema.sql
│   └── seed.php
├── public/
│   ├── css/style.css
│   ├── js/api.js
│   └── index.php
├── routes/web.php
├── views/
├── .env
├── composer.json
└── README.md
```

## 6. Instalación paso a paso

1. Copiar el proyecto en el servidor local.
2. Ejecutar `composer install` para instalar dependencias.
3. Crear la base de datos `hotel_luna_azul` en MySQL.
4. Importar `database/schema.sql`.
5. Ejecutar `php database/seed.php` para datos iniciales.
6. Configurar `.env` con credenciales de BD.
7. Iniciar el servidor.

## 7. Arranque

```bash
php -S localhost:8000 -t public
```

Abrir en el navegador: http://localhost:8000/login

Credenciales de acceso por defecto:
- Email: admin@hotel.com
- Contraseña: admin123

## 8. Base de datos

Tablas principales:
- `usuarios`: credenciales de acceso.
- `huespedes`: datos personales del huésped.
- `habitaciones`: número, tipo, capacidad, precio, estado.
- `reservas`: relación huésped-habitación con fechas y estado.

## 9. Endpoints de la API

Autenticación:
- POST `/api/login` - iniciar sesión.
- POST `/api/logout` - cerrar sesión.
- GET `/api/me` - usuario autenticado.

Huéspedes:
- GET `/api/huespedes` - listar (soporta `?search=`).
- GET `/api/huespedes/{id}` - detalle.
- POST `/api/huespedes` - crear.
- PUT `/api/huespedes/{id}` - actualizar.
- DELETE `/api/huespedes/{id}` - eliminar.

Habitaciones:
- GET `/api/habitaciones` - listar.
- GET `/api/habitaciones/{id}` - detalle.
- POST `/api/habitaciones` - crear.
- PUT `/api/habitaciones/{id}` - actualizar.
- DELETE `/api/habitaciones/{id}` - eliminar.

Reservas:
- GET `/api/reservas` - listar.
- GET `/api/reservas/{id}` - detalle.
- POST `/api/reservas` - crear.
- PUT `/api/reservas/{id}` - actualizar.
- DELETE `/api/reservas/{id}` - eliminar.
- GET `/api/disponibilidad` - habitaciones disponibles por fechas y tipo.

## 10. Consumo de la API en el frontend

El archivo `public/js/api.js` define el objeto `API` con los métodos `get`, `post`, `put` y `del`. Todas las vistas en `views/*.php` usan este objeto para comunicarse con el backend. Ejemplo:

```js
const r = await API.get('/api/huespedes');
r.data.forEach(h => { ... });
```

El backend responde siempre en formato JSON con la clave `data` (éxito) o `error`/`errors` (fallo).

## 11. Vistas del sistema

- `login.php`: formulario de acceso.
- `index.php`: dashboard con métricas y accesos rápidos.
- `huespedes.php`: listado, búsqueda y modal CRUD.
- `habitaciones.php`: listado, badges de estado y modal CRUD.
- `reservas.php`: listado, verificación de disponibilidad y modal CRUD.

## 12. Validaciones y reglas de negocio

- No se permite doble asignación de una habitación en fechas solapadas.
- Validación de fechas de ingreso y salida.
- Validación de campos requeridos en formularios.
- Autenticación obligatoria para acceder a las vistas protegidas.

## 13. Estilo y experiencia de usuario

Las vistas cuentan con un diseño profesional:
- Navbar con gradiente y marca.
- Tarjetas con sombra y efecto hover.
- Badges de estado (confirmada, cancelada, disponible, mantenimiento).
- Modales con animación de entrada.
- Toasts de notificación (éxito/error).
- Diseño responsivo para móviles.

## 14. Mantenimiento y extensión

Para agregar un nuevo módulo:
1. Crear el modelo en `app/Models`.
2. Crear el controlador en `app/Controllers`.
3. Registrar rutas en `routes/web.php`.
4. Crear la vista en `views/`.
5. Consumir la API desde la vista con `API`.

## 15. Créditos

Proyecto desarrollado para la asignatura de desarrollo web (universidad). Patrón MVC inspirado en Laravel (standalone).