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
    public string $ubicacion    = '';
    public string $conductor    = '';
    public string $descripcion  = '';
    public float $presupuesto   = 0.0;

    // Metadata de la póliza (del SP validar-poliza)
    public float $suma_asegurada = 0.0;

    public function __construct(array $args = [])
    {
        $this->poliza_id      = (int)   ($args['poliza_id']    ?? 0);
        $this->ajustador_id   = (int)   ($args['ajustador_id'] ?? 0);
        $this->fecha_hora     =          $args['fecha_hora']   ?? '';
        $this->ubicacion      =          $args['ubicacion']    ?? '';
        $this->conductor      =          $args['conductor']    ?? '';
        $this->descripcion    =          $args['descripcion']  ?? '';
        $this->presupuesto    = (float) ($args['presupuesto']  ?? 0);
        $this->suma_asegurada = (float) ($args['suma_asegurada'] ?? 0);
    }

    public function validar(): array
    {
        self::$errores = [];

        if (!$this->poliza_id)    self::$errores[] = 'La póliza es obligatoria.';
        if (!$this->ajustador_id) self::$errores[] = 'El ajustador es obligatorio.';
        if (empty($this->fecha_hora))  self::$errores[] = 'La fecha y hora son obligatorias.';
        if (empty($this->conductor))   self::$errores[] = 'El conductor es obligatorio.';
        if (empty($this->descripcion)) self::$errores[] = 'La descripción es obligatoria.';

        if ($this->presupuesto < 0) {
            self::$errores[] = 'El presupuesto no puede ser negativo.';
        }
        if ($this->suma_asegurada > 0 && $this->presupuesto > $this->suma_asegurada) {
            self::$errores[] = 'El presupuesto excede la suma asegurada ($' . number_format($this->suma_asegurada, 2) . ').';
        }

        return self::$errores;
    }

    public function registrar(): int
    {
        $resultado = self::call_sp('sp_registrar_siniestro', [
            $this->poliza_id,
            $this->ajustador_id,
            $this->fecha_hora,
            $this->ubicacion,
            $this->conductor,
            $this->descripcion,
            $this->presupuesto,
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
        $rows = self::call_sp('sp_get_catalogo_estatus_siniestros');
        return $rows;
    }
}
