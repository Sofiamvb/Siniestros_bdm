<?php

namespace Controllers;

use Model\Vehiculo;
use Model\Seguro;
use Model\Poliza;
use MVC\Router;

class ContratarController
{
    /** Verifica que haya sesión activa de asegurado. */
    private static function verificarSesion(): void
    {
        if (empty($_SESSION['id']) || ($_SESSION['rol_id'] ?? 0) !== 1) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Paso 1 — GET /contratar?vehiculo_id=X
     * Muestra las opciones de seguro disponibles con prima calculada.
     */
    public static function elegirSeguro(Router $router): void
    {
        self::verificarSesion();

        $vehiculoId = (int) ($_GET['vehiculo_id'] ?? 0);
        $vehiculo   = $vehiculoId ? Vehiculo::obtenerPorId($vehiculoId) : null;

        if (!$vehiculo) {
            header('Location: /cotizar');
            exit;
        }

        $seguros = Seguro::obtenerDisponibles($vehiculoId);

        // Agregar prima calculada a cada seguro
        foreach ($seguros as $seguro) {
            $seguro->prima = $seguro->calcularPrima($vehiculo->precio_seguro);
        }

        $router->render('paginas/contratar', [
            'vehiculo' => $vehiculo,
            'seguros'  => $seguros,
        ]);
    }

    /**
     * Paso 2 — GET /pago?vehiculo_id=X&seguro_id=Y
     *           POST /pago → crea la póliza
     */
    public static function pago(Router $router): void
    {
        self::verificarSesion();

        $vehiculoId = (int) ($_GET['vehiculo_id'] ?? $_POST['vehiculo_id'] ?? 0);
        $seguroId   = (int) ($_GET['seguro_id']   ?? $_POST['seguro_id']   ?? 0);

        $vehiculo = $vehiculoId ? Vehiculo::obtenerPorId($vehiculoId) : null;
        $seguro   = $seguroId   ? Seguro::obtenerPorId($seguroId)     : null;

        if (!$vehiculo || !$seguro) {
            header('Location: /cotizar');
            exit;
        }

        $prima   = $seguro->calcularPrima($vehiculo->precio_seguro);
        $errores = [];
        $exito   = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $poliza = new Poliza([
                'usuario_id'           => $_SESSION['id'],
                'seguro_id'            => $seguroId,
                'catalogo_vehiculo_id' => $vehiculoId,
                'placas'               => $_POST['placas'] ?? '',
                'fecha_inicio'         => date('Y-m-d'),
                'fecha_fin'            => date('Y-m-d', strtotime('+1 year')),
            ]);

            $errores = $poliza->validar();

            if (!$errores) {
                $polizaId = $poliza->contratar();
                if ($polizaId) {
                    header('Location: /siniestrosAsegurados?poliza_nueva=1');
                    exit;
                }
                $errores[] = 'Ocurrió un error al procesar la contratación. Intenta de nuevo.';
            }
        }

        $router->render('paginas/pago', [
            'vehiculo' => $vehiculo,
            'seguro'   => $seguro,
            'prima'    => $prima,
            'errores'  => $errores,
        ]);
    }
}
