<?php

namespace Controllers;

use Model\Ajustador;
use Model\Siniestro;
use MVC\Router;

class AjustadoresController
{
    public static function detalle(Router $router): void
    {
        $id        = (int) ($_GET['id'] ?? 0);
        $siniestro = $id ? Siniestro::obtenerDetalle($id) : null;

        if (!$siniestro || (int) $siniestro['ajustador_id'] !== (int) $_SESSION['id']) {
            header('Location: /siniestrosAjustadores');
            exit;
        }

        $evidencias = array_map(function (array $e) {
            $e['src'] = \Model\ActiveRecord::blobToImg($e['archivo_multimedia'], $e['tipo_mime']) ?: '';
            unset($e['archivo_multimedia']);
            return $e;
        }, Siniestro::obtenerEvidencias($id));

        $seguimiento = Siniestro::obtenerSeguimiento($id);

        // Siniestros anteriores a la migración 28 no tienen seguimiento registrado.
        // Se genera una entrada sintética con el estado actual para que el timeline
        // nunca quede vacío.
        if (empty($seguimiento)) {
            $seguimiento = [[
                'fecha_movimiento' => $siniestro['fecha_hora_siniestro'],
                'estatus'          => $siniestro['estatus'],
                'estatus_color'    => $siniestro['estatus_color'],
                'comentario_publico' => 'Siniestro registrado',
                'usuario_nombre'   => $siniestro['ajustador_nombre'],
            ]];
        }

        $router->render('paginas/detallesSiniestrosAjustador', [
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

        if (!$siniestro || (int)$siniestro['ajustador_id'] !== $usuarioId) {
            header('Location: /siniestrosAjustadores'); exit;
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
        $router->render('paginas/buscadorSiniestrosAjustador', []);
    }

    public static function apiBuscar(): void
    {
        header('Content-Type: application/json');
        $termino = trim($_GET['q'] ?? '');
        if (strlen($termino) < 2) { echo json_encode([]); exit; }
        echo json_encode(Siniestro::buscar($termino, (int) $_SESSION['rol_id'], (int) $_SESSION['id']));
        exit;
    }

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
