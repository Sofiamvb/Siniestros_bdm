<?php

namespace Controllers;

use Model\Poliza;
use MVC\Router;

class AseguradosController
{
    public static function siniestros(Router $router): void
    {
        if (empty($_SESSION['id']) || ($_SESSION['rol_id'] ?? 0) !== 1) {
            header('Location: /login');
            exit;
        }

        $polizas = Poliza::obtenerPorUsuario((int) $_SESSION['id']);

        $router->render('paginas/siniestrosAsegurados', [
            'polizas' => $polizas,
        ]);
    }
}
