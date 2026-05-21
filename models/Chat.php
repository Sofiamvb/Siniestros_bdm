<?php

namespace Model;

class Chat extends ActiveRecord
{
    public static function obtenerPorSiniestro(int $siniestroId): int
    {
        $filas = self::call_sp('sp_get_chat_by_siniestro', [$siniestroId]);
        return (int) ($filas[0]['chat_id'] ?? 0);
    }

    public static function enviarMensaje(
        int $chatId, int $usuarioId, string $mensaje,
        ?string $imagen = null, ?string $imagenMime = null, ?string $videoRuta = null
    ): ?array {
        $filas = self::call_sp('sp_enviar_mensaje', [
            $chatId, $usuarioId, $mensaje, $imagen, $imagenMime, $videoRuta,
        ]);
        return $filas[0] ?? null;
    }

    public static function obtenerMensajes(int $chatId, int $desdeId = 0): array
    {
        return self::call_sp('sp_get_mensajes_chat', [$chatId, $desdeId]);
    }

    public static function verificarParticipante(int $siniestroId, int $usuarioId): bool
    {
        $filas = self::call_sp('sp_verificar_participante_chat', [$siniestroId, $usuarioId]);
        return (int) ($filas[0]['es_participante'] ?? 0) > 0;
    }
}
