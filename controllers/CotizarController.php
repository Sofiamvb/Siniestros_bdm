<?php

namespace Controllers;

use Model\Vehiculo;
use MVC\Router;

class CotizarController
{
    /**
     * Endpoint JSON — devuelve todas las marcas.
     * GET /api/marcas
     */
    public static function apiMarcas(Router $router): void
    {
        header('Content-Type: application/json');
        echo json_encode(Vehiculo::get_marcas_vehiculos());
        exit;
    }

    /**
     * Endpoint JSON — devuelve modelos para la marca recibida por GET.
     * GET /api/modelos?marca=Nissan
     */
    public static function apiModelos(Router $router): void
    {
        $marca   = trim($_GET['marca'] ?? '');
        $modelos = $marca ? Vehiculo::get_modelos_by_marca($marca) : [];

        header('Content-Type: application/json');
        echo json_encode($modelos);
        exit;
    }

    /**
     * Endpoint JSON — devuelve años para marca + modelo.
     * GET /api/anios?marca=Nissan&modelo=Versa
     */
    public static function apiAnios(Router $router): void
    {
        $marca  = trim($_GET['marca']  ?? '');
        $modelo = trim($_GET['modelo'] ?? '');
        $anios  = ($marca && $modelo) ? Vehiculo::get_anios_by_modelo($marca, $modelo) : [];

        header('Content-Type: application/json');
        echo json_encode($anios);
        exit;
    }

    /**
     * Página de cotización.
     * - GET con marca+modelo+anio en query params → cotiza automáticamente (viene del landing).
     * - GET sin params → muestra el formulario vacío.
     * - POST → cotiza desde el formulario interno.
     */
    public static function cotizar(Router $router): void
    {
        $errores  = [];
        $vehiculo = null;
        $marcas   = Vehiculo::get_marcas_vehiculos();

        $params = $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : $_GET;
        $tiene_params = !empty($params['marca']) && !empty($params['modelo']) && !empty($params['anio']);

        if ($tiene_params) {
            $v       = new Vehiculo($params);
            $errores = $v->validarCotizacion();

            if (!$errores) {
                $vehiculo = $v->cotizar();
                if (!$vehiculo) {
                    $errores[] = 'No encontramos ese vehículo en nuestro catálogo.';
                }
            }
        }

        $router->render('paginas/cotizar', [
            'marcas'   => $marcas,
            'errores'  => $errores,
            'vehiculo' => $vehiculo,
            'post'     => $params,
        ]);
    }
}
