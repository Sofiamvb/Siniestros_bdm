<?php

namespace Controllers;

use Model\Chat;
use MVC\Router;

class ChatController
{
    public static function obtenerMensajes(Router $router): void
    {
        header('Content-Type: application/json');

        if (empty($_SESSION['id'])) {
            echo json_encode(['error' => 'No autorizado']); exit;
        }

        $siniestroId = (int) ($_GET['siniestro_id'] ?? 0);
        $desdeId     = (int) ($_GET['desde_id']     ?? 0);

        if (!$siniestroId) {
            echo json_encode(['error' => 'siniestro_id requerido']); exit;
        }

        if (!Chat::verificarParticipante($siniestroId, (int) $_SESSION['id'])) {
            echo json_encode(['error' => 'Acceso no permitido']); exit;
        }

        $chatId   = Chat::obtenerPorSiniestro($siniestroId);
        $mensajes = Chat::obtenerMensajes($chatId, $desdeId);

        echo json_encode(['chat_id' => $chatId, 'mensajes' => $mensajes]);
        exit;
    }

    public static function enviarMensaje(Router $router): void
    {
        header('Content-Type: application/json');

        if (empty($_SESSION['id'])) {
            echo json_encode(['error' => 'No autorizado']); exit;
        }

        $siniestroId = (int)    ($_POST['siniestro_id'] ?? 0);
        $mensaje     = trim($_POST['mensaje']           ?? '');

        if (!$siniestroId || $mensaje === '') {
            echo json_encode(['error' => 'Datos inválidos']); exit;
        }

        if (!Chat::verificarParticipante($siniestroId, (int) $_SESSION['id'])) {
            echo json_encode(['error' => 'Acceso no permitido']); exit;
        }

        $chatId  = Chat::obtenerPorSiniestro($siniestroId);
        $nuevo   = Chat::enviarMensaje($chatId, (int) $_SESSION['id'], $mensaje);

        echo json_encode(['ok' => true, 'mensaje' => $nuevo]);
        exit;
    }
}
