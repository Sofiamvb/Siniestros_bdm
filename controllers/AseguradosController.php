<?php

namespace Controllers;

use Model\Poliza;
use Model\Siniestro;
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

    public static function buscador(Router $router): void
    {
        $router->render('paginas/buscadorSiniestrosAsegurado', []);
    }

    public static function apiBuscar(): void
    {
        header('Content-Type: application/json');
        $termino = trim($_GET['q'] ?? '');
        if (strlen($termino) < 2) { echo json_encode([]); exit; }
        echo json_encode(Siniestro::buscar($termino, (int) $_SESSION['rol_id'], (int) $_SESSION['id']));
        exit;
    }
}
