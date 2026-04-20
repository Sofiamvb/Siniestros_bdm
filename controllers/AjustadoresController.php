<?php

namespace Controllers;

use Model\Ajustador;
use MVC\Router;

class AjustadoresController
{
    public static function register(Router $router): void
    {
        // El Router ya protege esta ruta a rol_id=3, pero validamos en el modelo también
        Ajustador::verificarRegistroPorSupervisor();

        $errores = [];
        $exito   = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ajustador                   = new Ajustador($_POST);
            $ajustador->password_confirm = $_POST['confirmPassword'] ?? '';

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $ajustador->foto = file_get_contents($_FILES['foto']['tmp_name']);
            }

            $errores = $ajustador->validar();

            if (!$errores) {
                $ajustador->registrar();
                $exito = 'Ajustador registrado correctamente.';
            }
        }

        $router->render('paginas/register-ajustador', [
            'errores' => $errores,
            'exito'   => $exito
        ]);
    }
}
