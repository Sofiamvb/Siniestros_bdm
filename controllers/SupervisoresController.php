<?php

namespace Controllers;

use Model\Supervisor;
use Model\Siniestro;
use MVC\Router;

class SupervisoresController
{
    public static function siniestros(Router $router): void
    {
        $supervisorId  = (int) $_SESSION['id'];
        $siniestrosRaw = Siniestro::obtenerTodos();

        $siniestros = [];
        foreach ($siniestrosRaw as $s) {
            $s['primera_evidencia'] = \Model\ActiveRecord::blobToImg(
                $s['primera_evidencia']      ?? null,
                $s['primera_evidencia_mime'] ?? 'image/jpeg'
            ) ?: '/img/siniestro.jpg';
            $s['es_mio'] = ((int) $s['supervisor_id']) === $supervisorId;
            $siniestros[] = $s;
        }

        $router->render('paginas/siniestrosSupervisores', [
            'siniestros'     => $siniestros,
            'siniestroNuevo' => !empty($_GET['siniestro_nuevo']),
        ]);
    }

    public static function detalle(Router $router): void
    {
        $id        = (int) ($_GET['id'] ?? 0);
        $siniestro = $id ? Siniestro::obtenerDetalle($id) : null;

        if (!$siniestro) {
            header('Location: /siniestrosSupervisores');
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

        $router->render('paginas/detallesSiniestrosSupervisor', [
            'siniestro'   => $siniestro,
            'evidencias'  => $evidencias,
            'terceros'    => Siniestro::obtenerTerceros($id),
            'seguimiento' => $seguimiento,
        ]);
    }

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
