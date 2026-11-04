<?php
// Punto de entrada: carga dependencias, BD, sesión y router
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Boot Eloquent
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => $_ENV['DB_CONNECTION'] ?? 'mysql',
    'host'      => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'port'      => $_ENV['DB_PORT'] ?? 3306,
    'database'  => $_ENV['DB_DATABASE'] ?? 'hotel_luna_azul',
    'username'  => $_ENV['DB_USERNAME'] ?? 'root',
    'password'  => $_ENV['DB_PASSWORD'] ?? '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../app/Core/Router.php';
require __DIR__ . '/../app/Core/Request.php';
require __DIR__ . '/../app/Core/Response.php';

$router = new App\Core\Router();

require __DIR__ . '/../routes/web.php';

$router->dispatch(App\Core\Request::capture());
