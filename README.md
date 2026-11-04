# Hotel Luna Azul - Sistema de Gestión de Reservas

## 📋 Descripción General

El **Hotel Luna Azul** actualmente gestiona sus reservas de forma manual (cuadernos físicos, llamadas telefónicas y mensajes por WhatsApp), lo que ha generado múltiples problemas operativos. Se requiere desarrollar un **sistema web en PHP** bajo el **patrón MVC**, con conexión a **MySQL**, que permita gestionar huéspedes y sus reservas de forma digital, accesible desde cualquier navegador.

---

## 🎯 Propósito de la Actividad

- Desarrollar un sistema web en PHP utilizando el patrón **MVC**, con conexión a base de datos **MySQL**, que permita gestionar pacientes/huéspedes.
- Desarrollar habilidades prácticas en la creación y gestión de aplicaciones web utilizando la arquitectura MVC.
- Aplicar conocimientos de **HTML5, CSS3, JavaScript, PHP, Python y bases de datos** en el desarrollo de una aplicación web completa.
- Evaluar la capacidad de los estudiantes para diseñar, implementar y gestionar una aplicación web funcional.

---

## 🛠️ Stack Tecnológico

| Componente | Tecnología |
|------------|-----------|
| Editor de código | Libre elección (VS Code, PHPStorm, etc.) |
| Servidor local | **XAMPP** (Apache + MySQL + PHP) |
| Backend | **PHP** (o cualquier lenguaje backend interpretado) |
| Base de datos | **MySQL** |
| Frontend | HTML5, CSS3, JavaScript, AJAX |
| ORM (ajuste del desarrollador) | **Eloquent (illuminate/database)** standalone |
| Gestor de dependencias | **Composer** |

---

## 📐 Arquitectura del Proyecto

### Patrón MVC
El proyecto debe seguir el patrón **Modelo-Vista-Controlador**, con una estructura inspirada en Laravel (standalone, sin framework completo).

### Estructura de Carpetas Propuesta
```text
/hotel-luna-azul
├── app/
│   ├── Controllers/       # Controladores (ej. HuespedController.php)
│   ├── Models/            # Modelos Eloquent (ej. Huesped.php)
│   └── Core/              # Clases base / Bootstrap
├── public/                # ÚNICO acceso público
│   ├── index.php          # Front Controller (Punto de entrada)
│   ├── css/
│   └── js/
├── vendor/                # Dependencias de Composer
├── views/                 # Vistas HTML/PHP
├── .env                   # Variables de entorno (DB)
└── composer.json



## ▶️ Arranque del proyecto

1. Instalar dependencias:
   ```bash
   composer install
   ```
2. Configurar `.env` (DB_HOST, DB_NAME, DB_USER, DB_PASS).
3. Importar `database/schema.sql` y ejecutar `database/seed.php`.
4. Iniciar servidor:
   ```bash
   php -S localhost:8000 -t public
   ```
5. Abrir en navegador: http://localhost:8000/login

**Credenciales de acceso:** admin@hotel.com / admin123

Backend completado: MVC PHP + Eloquent + MySQL, rutas API (auth, huéspedes, habitaciones, reservas, disponibilidad), validación de fechas y anti-doble-asignación. DB hotel_luna_azul creada.