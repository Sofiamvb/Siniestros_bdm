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

    public static function detallePoliza(Router $router): void
    {
        $polizaId  = (int) ($_GET['id'] ?? 0);
        $usuarioId = (int) $_SESSION['id'];
        $poliza    = $polizaId ? Poliza::obtenerDetalle($polizaId, $usuarioId) : null;

        if (!$poliza) {
            header('Location: /siniestrosAsegurados');
            exit;
        }

        $router->render('paginas/detallePoliza', ['poliza' => $poliza]);
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

        // Videos guardados en filesystem (no en BD)
        $numeroReporte = $siniestro['numero_reporte'] ?? '';
        if ($numeroReporte) {
            $dirVideos = dirname(__DIR__, 2) . '/public/videos/' . $numeroReporte . '/';
            if (is_dir($dirVideos)) {
                foreach (glob($dirVideos . '*') as $rutaArchivo) {
                    $evidencias[] = [
                        'src'             => '/videos/' . $numeroReporte . '/' . basename($rutaArchivo),
                        'tipo'            => 'video',
                        'nombre'          => basename($rutaArchivo),
                        'tipo_evidencia'  => 'video',
                        'tipo_mime'       => 'video/mp4',
                    ];
                }
            }
        }

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

    public static function chat(Router $router): void
    {
        $siniestroId = (int) ($_GET['siniestro_id'] ?? 0);
        $usuarioId   = (int) $_SESSION['id'];
        $siniestro   = $siniestroId ? Siniestro::obtenerDetalle($siniestroId) : null;

        if (!$siniestro || !$siniestro || (int)$siniestro['usuario_id'] !== $usuarioId) {
            header('Location: /siniestrosAsegurados'); exit;
        }

        $chatId   = \Model\Chat::obtenerPorSiniestro($siniestroId);
        $mensajes = \Model\Chat::obtenerMensajes($chatId);

        $router->render('paginas/chatSiniestro', [
            'siniestro' => $siniestro,
            'mensajes'  => $mensajes,
            'volverUrl' => "/siniestro?id={$siniestroId}",
        ]);
    }

    public static function buscador(Router $router): void
    {
        $usuarioId = (int) $_SESSION['id'];
        $inicio    = !empty($_GET['desde']) ? $_GET['desde'] : null;
        $fin       = !empty($_GET['hasta']) ? $_GET['hasta'] : null;

        $raw = Siniestro::obtenerPorAsegurado($usuarioId, $inicio, $fin);

        $siniestros = array_map(function (array $s) {
            $s['primera_evidencia'] = \Model\ActiveRecord::blobToImg(
                $s['primera_evidencia']      ?? null,
                $s['primera_evidencia_mime'] ?? 'image/jpeg'
            ) ?: '/img/siniestro.jpg';
            return $s;
        }, $raw);

        $router->render('paginas/buscadorSiniestrosAsegurado', [
            'siniestros' => $siniestros,
        ]);
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
