<?php

namespace Model;

class Siniestro extends ActiveRecord
{
    protected static $tabla = 'siniestros';
    protected static array $errores = [];

    public $id;
    public int $poliza_id       = 0;
    public int $ajustador_id    = 0;
    public string $fecha_hora   = '';
    public string $latitud      = '';
    public string $longitud     = '';
    public string $conductor    = '';
    public string $descripcion  = '';
    public float $presupuesto    = 0.0;
    public bool  $perdida_total  = false;

    // Metadata de la póliza (del SP validar-poliza)
    public float $suma_asegurada = 0.0;

    public function __construct(array $args = [])
    {
        $this->poliza_id      = (int)   ($args['poliza_id']    ?? 0);
        $this->ajustador_id   = (int)   ($args['ajustador_id'] ?? 0);
        $this->fecha_hora     =          $args['fecha_hora']   ?? '';
        $this->latitud        =          $args['latitud']      ?? '';
        $this->longitud       =          $args['longitud']     ?? '';
        $this->conductor      =          $args['conductor']    ?? '';
        $this->descripcion    =          $args['descripcion']  ?? '';
        $this->perdida_total  = (bool)  ($args['perdida_total']  ?? false);
        $this->presupuesto    = (float) ($args['presupuesto']    ?? 0);
        $this->suma_asegurada = (float) ($args['suma_asegurada'] ?? 0);
    }

    public function validar(): array
    {
        self::$errores = [];

        if (!$this->poliza_id)    self::$errores[] = 'La póliza es obligatoria.';
        if (!$this->ajustador_id) self::$errores[] = 'El ajustador es obligatorio.';
        if (empty($this->fecha_hora))  self::$errores[] = 'La fecha y hora son obligatorias.';
        if (empty($this->latitud))     self::$errores[] = 'La latitud es obligatoria.';
        if (empty($this->longitud))    self::$errores[] = 'La longitud es obligatoria.';
        if (empty($this->conductor))   self::$errores[] = 'El conductor es obligatorio.';
        if (empty($this->descripcion)) self::$errores[] = 'La descripción es obligatoria.';

        if (!$this->perdida_total) {
            if ($this->presupuesto <= 0) {
                self::$errores[] = 'El presupuesto es obligatorio cuando no es pérdida total.';
            } elseif ($this->suma_asegurada > 0 && $this->presupuesto > $this->suma_asegurada) {
                self::$errores[] = 'El presupuesto excede la suma asegurada ($' . number_format($this->suma_asegurada, 2) . ').';
            }
        }

        return self::$errores;
    }

    public function registrar(): int
    {
        $resultado = self::call_sp('sp_registrar_siniestro', [
            $this->poliza_id,
            $this->ajustador_id,
            $this->fecha_hora,
            $this->latitud,
            $this->longitud,
            $this->conductor,
            $this->descripcion,
            $this->presupuesto,
            (int) $this->perdida_total,
        ]);

        $this->id = (int) ($resultado[0]['id'] ?? 0);
        return $this->id;
    }

    public function registrarEvidencia(int $siniestroId, string $binario, string $nombre, string $mime): void
    {
        $tipo = str_starts_with($mime, 'video/') ? 'video' : 'imagen';
        self::call_sp('sp_registrar_evidencia_siniestro', [
            $siniestroId,
            $this->ajustador_id,
            $binario,
            $nombre,
            $mime,
            $tipo,
        ]);
    }

    public function registrarTercero(int $siniestroId, array $t): void
    {
        self::call_sp('sp_registrar_tercero_involucrado', [
            $siniestroId,
            $t['marca']       ?? '',
            $t['modelo']      ?? '',
            $t['placas']      ?? '',
            $t['aseguradora'] ?? '',
            $t['descripcion'] ?? '',
        ]);
    }

    public static function validarPoliza(string $numero): ?array
    {
        $rows = self::call_sp('sp_get_poliza_by_numero', [$numero]);
        return $rows[0] ?? null;
    }

    public static function obtenerEstatusDisponibles(): array
    {
        return self::call_sp('sp_get_catalogo_estatus_siniestros');
    }

    public static function obtenerPorAjustador(int $ajustadorId): array
    {
        return self::call_sp('sp_get_siniestros_ajustador', [$ajustadorId]);
    }

    public static function obtenerTodos(): array
    {
        return self::call_sp('sp_get_siniestros_supervisor');
    }

    public static function buscar(string $termino, int $rolId, int $usuarioId): array
    {
        return self::call_sp('sp_buscar_siniestros', [$termino, $rolId, $usuarioId]);
    }

    public static function obtenerDetalle(int $id): ?array
    {
        $filas = self::call_sp('sp_get_siniestro_detalle', [$id]);
        return $filas[0] ?? null;
    }

    public static function obtenerEvidencias(int $id): array
    {
        return self::call_sp('sp_get_evidencias_siniestro', [$id]);
    }

    public static function obtenerTerceros(int $id): array
    {
        return self::call_sp('sp_get_terceros_siniestro', [$id]);
    }

    public static function obtenerSeguimiento(int $id): array
    {
        return self::call_sp('sp_get_seguimiento_siniestro', [$id]);
    }
}
