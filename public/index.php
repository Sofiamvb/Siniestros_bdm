<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Router.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ActiveRecord.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Supervisor.php';
require_once __DIR__ . '/../controllers/PaginasController.php';
require_once __DIR__ . '/../controllers/SupervisoresController.php';
require __DIR__ . '/../vendor/autoload.php';

use MVC\Router;
use Controllers\PaginasController;
use Controllers\SupervisoresController;
use Model\ActiveRecord;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$db = conectarDb();
ActiveRecord::setDB($db);

$router = new Router();

// Registros Aseguradores
$router->get('/register',            [PaginasController::class, 'register']);
$router->post('/register',           [PaginasController::class, 'register']);

// Registro de Supervisores (protegido por token)
$router->get('/acceso-supervisores',   [SupervisoresController::class, 'accesoToken']);
$router->post('/acceso-supervisores',  [SupervisoresController::class, 'accesoToken']);
$router->get('/register/supervisores', [SupervisoresController::class, 'register']);
$router->post('/register/supervisores',[SupervisoresController::class, 'register']);


$router->get('/',                    [PaginasController::class, 'landingPage']);
$router->get('/login',               [PaginasController::class, 'login']);
$router->post('/login',              [PaginasController::class, 'login']);
$router->get('/logout',              [PaginasController::class, 'logout']);
$router->get('/registrarSiniestros', [PaginasController::class, 'registrarSiniestros']);
$router->get('/siniestrosAjustadores', [PaginasController::class, 'siniestrosAjustadores']);
$router->get('/siniestrosAsegurados',   [PaginasController::class, 'siniestrosAsegurados']);
$router->get('/siniestrosSupervisores', [PaginasController::class, 'siniestrosSupervisores']);

$router->comprobarRutas();
