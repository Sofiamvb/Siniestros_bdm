<?php

namespace Controllers;

use Model\Supervisor;
use Model\Siniestro;
use MVC\Router;

class SupervisoresController
{
    public static function buscador(Router $router): void
    {
        $router->render('paginas/buscadorSiniestrosSupervisor', []);
    }

    public static function apiBuscar(): void
    {
        header('Content-Type: application/json');
        $termino = trim($_GET['q'] ?? '');
        if (strlen($termino) < 2) { echo json_encode([]); exit; }
        echo json_encode(Siniestro::buscar($termino, (int) $_SESSION['rol_id'], (int) $_SESSION['id']));
        exit;
    }

    public static function accesoToken(Router $router): void
    {
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token   = trim($_POST['token'] ?? '');
            $errores = Supervisor::verificarAccesoToken($token);

            if (!$errores) {
                $_SESSION['supervisor_acceso'] = true;
                header('Location: /register/supervisores');
                exit;
            }
        }

        $router->render('paginas/acceso-supervisores', ['errores' => $errores]);
    }

    public static function register(Router $router): void
    {
        Supervisor::verificarAcceso();

        $errores = [];
        $exito   = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $supervisor                   = new Supervisor($_POST);
            $supervisor->password_confirm = $_POST['confirmPassword'] ?? '';

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $supervisor->foto = file_get_contents($_FILES['foto']['tmp_name']);
            }

            $errores = $supervisor->validar();

            if (!$errores) {
                $supervisor->registrar();
                unset($_SESSION['supervisor_acceso']);
                $exito = 'Supervisor registrado correctamente.';
            }
        }

        $router->render('paginas/register-supervisor', [
            'errores' => $errores,
            'exito'   => $exito
        ]);
    }
}
