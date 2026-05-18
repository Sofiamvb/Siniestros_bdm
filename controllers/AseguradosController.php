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

    public static function detalle(Router $router): void
    {
        $id        = (int) ($_GET['id'] ?? 0);
        $siniestro = $id ? Siniestro::obtenerDetalle($id) : null;

        if (!$siniestro || (int) $siniestro['usuario_id'] !== (int) $_SESSION['id']) {
            header('Location: /siniestrosAsegurados');
            exit;
        }

        $evidencias = array_map(function (array $e) {
            $e['src'] = \Model\ActiveRecord::blobToImg($e['archivo_multimedia'], $e['tipo_mime']) ?: '';
            unset($e['archivo_multimedia']);
            return $e;
        }, Siniestro::obtenerEvidencias($id));

        $seguimiento = Siniestro::obtenerSeguimiento($id);

        if (empty($seguimiento)) {
            $seguimiento = [[
                'fecha_movimiento'   => $siniestro['fecha_hora_siniestro'],
                'estatus'            => $siniestro['estatus'],
                'estatus_color'      => $siniestro['estatus_color'],
                'comentario_publico' => 'Siniestro registrado',
                'usuario_nombre'     => $siniestro['ajustador_nombre'],
            ]];
        }

        $router->render('paginas/detallesSiniestrosAsegurado', [
            'siniestro'   => $siniestro,
            'evidencias'  => $evidencias,
            'terceros'    => Siniestro::obtenerTerceros($id),
            'seguimiento' => $seguimiento,
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
