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

        // Convertir BLOB de imagen a data URI para cada mensaje
        foreach ($mensajes as &$m) {
            if (!empty($m['imagen'])) {
                $m['imagen_src'] = \Model\ActiveRecord::blobToImg($m['imagen'], $m['imagen_mime'] ?? 'image/jpeg');
            }
            unset($m['imagen']);
        }

        echo json_encode(['chat_id' => $chatId, 'mensajes' => $mensajes]);
        exit;
    }

    public static function enviarMensaje(Router $router): void
    {
        header('Content-Type: application/json');

        if (empty($_SESSION['id'])) {
            echo json_encode(['error' => 'No autorizado']); exit;
        }

        $siniestroId = (int) ($_POST['siniestro_id'] ?? 0);
        $mensaje     = trim($_POST['mensaje']         ?? '');

        // Procesar adjunto opcional (imagen o video)
        $imagen = $imagenMime = $videoRuta = null;

        if (!empty($_FILES['adjunto']) && $_FILES['adjunto']['error'] === UPLOAD_ERR_OK) {
            $mime = $_FILES['adjunto']['type'];
            if (str_starts_with($mime, 'video/')) {
                $ext      = strtolower(pathinfo($_FILES['adjunto']['name'], PATHINFO_EXTENSION) ?: 'mp4');
                $chatId   = Chat::obtenerPorSiniestro($siniestroId);
                $filename = $chatId . '_' . date('Y-m-d_H-i-s') . '.' . $ext;
                $dir      = dirname(__DIR__) . '/public/chat-videos/' . $chatId . '/';
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                move_uploaded_file($_FILES['adjunto']['tmp_name'], $dir . $filename);
                $videoRuta = '/chat-videos/' . $chatId . '/' . $filename;
            } else {
                $imagen     = file_get_contents($_FILES['adjunto']['tmp_name']);
                $imagenMime = $mime;
            }
        }

        if (!$siniestroId || ($mensaje === '' && !$imagen && !$videoRuta)) {
            echo json_encode(['error' => 'Datos inválidos']); exit;
        }

        if (!Chat::verificarParticipante($siniestroId, (int) $_SESSION['id'])) {
            echo json_encode(['error' => 'Acceso no permitido']); exit;
        }

        $chatId = $chatId ?? Chat::obtenerPorSiniestro($siniestroId);
        if (!$chatId) {
            echo json_encode(['error' => 'Chat no encontrado para este siniestro']); exit;
        }

        $nuevo = Chat::enviarMensaje($chatId, (int) $_SESSION['id'], $mensaje, $imagen, $imagenMime, $videoRuta);

        // Convertir BLOB a data URI antes de devolver al cliente
        if (!empty($nuevo['imagen'])) {
            $nuevo['imagen_src'] = \Model\ActiveRecord::blobToImg($nuevo['imagen'], $nuevo['imagen_mime'] ?? 'image/jpeg');
        }
        unset($nuevo['imagen']);

        echo json_encode(['ok' => true, 'mensaje' => $nuevo]);
        exit;
    }
}
