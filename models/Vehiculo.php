<?php

namespace Model;

class Vehiculo extends ActiveRecord
{
    protected static array $errores = [];

    public int    $id               = 0;
    public string $marca            = '';
    public string $modelo           = '';
    public int    $anio             = 0;
    public string $version          = '';
    public string $tipo_vehiculo    = '';
    public int    $numero_pasajeros = 0;
    public int    $cilindros        = 0;
    public float  $precio_seguro    = 0.0;

    public function __construct(array $args = [])
    {
        $this->marca   = trim($args['marca']   ?? '');
        $this->modelo  = trim($args['modelo']  ?? '');
        $this->anio    = (int) ($args['anio']  ?? 0);
        $this->version = trim($args['version'] ?? '');
    }

    // ── Validación ──────────────────────────────────────────────────────────

    public function validarCotizacion(): array
    {
        self::$errores = [];

        if (!$this->marca)   self::$errores[] = 'La marca es obligatoria.';
        if (!$this->modelo)  self::$errores[] = 'El modelo es obligatorio.';
        if (!$this->version) self::$errores[] = 'La versión es obligatoria.';
        if ($this->anio < 1900 || $this->anio > (int) date('Y') + 1) {
            self::$errores[] = 'El año no es válido.';
        }

        return self::$errores;
    }

    // ── Búsqueda por id ──────────────────────────────────────────────────────

    /** Retorna un objeto Vehiculo por su id o null si no existe. */
    public static function obtenerPorId(int $id): ?self
    {
        $filas = self::call_sp('sp_get_vehiculo_by_id', [$id]);
        if (empty($filas)) return null;

        $v = new self();
        foreach ($filas[0] as $campo => $valor) {
            if (property_exists($v, $campo)) $v->$campo = $valor;
        }
        return $v;
    }

    // ── Catálogo (cascading selects) ─────────────────────────────────────────

    /** Devuelve lista plana de marcas: ['BMW', 'Ford', ...] */
    public static function get_marcas_vehiculos(): array
    {
        $filas = self::call_sp('sp_get_marcas_vehiculos');
        return array_column($filas, 'marca');
    }

    /** Devuelve lista plana de modelos para una marca */
    public static function get_modelos_by_marca(string $marca): array
    {
        $filas = self::call_sp('sp_get_modelos_by_marca', [$marca]);
        return array_column($filas, 'modelo');
    }

    /** Devuelve lista plana de años para marca + modelo */
    public static function get_anios_by_modelo(string $marca, string $modelo): array
    {
        $filas = self::call_sp('sp_get_anios_by_modelo', [$marca, $modelo]);
        return array_column($filas, 'anio');
    }

    /** Devuelve lista plana de versiones para marca + modelo + año */
    public static function get_versiones_by_anio(string $marca, string $modelo, int $anio): array
    {
        $filas = self::call_sp('sp_get_versiones_by_anio', [$marca, $modelo, $anio]);
        return array_column($filas, 'version');
    }

    // ── Cotización ──────────────────────────────────────────────────────────

    /**
     * Llama sp_cotizar_vehiculo y retorna un objeto Vehiculo o null si no existe.
     */
    public function cotizar(): ?self
    {
        $filas = self::call_sp('sp_cotizar_vehiculo', [
            $this->marca,
            $this->modelo,
            $this->anio,
            $this->version,
        ]);

        if (empty($filas)) return null;

        $v = new self();
        foreach ($filas[0] as $campo => $valor) {
            if (property_exists($v, $campo)) {
                $v->$campo = $valor;
            }
        }
        return $v;
    }
}
