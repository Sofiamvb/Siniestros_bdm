<?php

namespace Controllers;

use Model\Supervisor;
use MVC\Router;

class SupervisoresController
{
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
