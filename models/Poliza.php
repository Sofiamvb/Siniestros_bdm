<?php

namespace Model;

class Poliza extends ActiveRecord
{
    protected static array $errores = [];

    public int    $id                   = 0;
    public int    $usuario_id           = 0;

    // Campos del JOIN (sp_get_polizas_by_usuario)
    public string $numero_poliza        = '';
    public string $placas               = '';
    public string $fecha_inicio         = '';
    public string $fecha_fin            = '';
    public string $estatus_poliza       = '';
    public string $marca                = '';
    public string $modelo               = '';
    public int    $anio                 = 0;
    public string $version              = '';
    public string $tipo_vehiculo        = '';
    public string $nombre_seguro        = '';
    public string $nivel                = '';
    public string $deducible_porcentaje = '';
    public string $compania             = '';
    public int    $seguro_id            = 0;
    public int    $catalogo_vehiculo_id = 0;

    public function __construct(array $args = [])
    {
        $this->usuario_id           = (int) ($args['usuario_id']           ?? 0);
        $this->seguro_id            = (int) ($args['seguro_id']            ?? 0);
        $this->catalogo_vehiculo_id = (int) ($args['catalogo_vehiculo_id'] ?? 0);
        $this->placas               = strtoupper(trim($args['placas']      ?? ''));
        $this->fecha_inicio         = $args['fecha_inicio'] ?? date('Y-m-d');
        $this->fecha_fin            = $args['fecha_fin']    ?? date('Y-m-d', strtotime('+1 year'));
    }

    public function validar(): array
    {
        self::$errores = [];

        if (empty($this->placas)) {
            self::$errores[] = 'Las placas son obligatorias.';
        } elseif (!preg_match('/^[A-Z0-9\-]{5,10}$/', $this->placas)) {
            self::$errores[] = 'Las placas no tienen un formato válido.';
        }

        if ($this->seguro_id <= 0) {
            self::$errores[] = 'Debes seleccionar un seguro.';
        }

        if ($this->catalogo_vehiculo_id <= 0) {
            self::$errores[] = 'El vehículo no es válido.';
        }

        if ($this->usuario_id <= 0) {
            self::$errores[] = 'Debes iniciar sesión para contratar.';
        }

        return self::$errores;
    }

    /**
     * Retorna todas las pólizas de un usuario con datos del vehículo y seguro.
     * @return self[]
     */
    public static function obtenerPorUsuario(int $usuarioId): array
    {
        $filas = self::call_sp('sp_get_polizas_by_usuario', [$usuarioId]);

        return array_map(function (array $fila) {
            $p = new self();
            foreach ($fila as $campo => $valor) {
                if (property_exists($p, $campo)) $p->$campo = $valor;
            }
            return $p;
        }, $filas);
    }

    /** Llama sp_contratar_poliza y retorna el id de la nueva póliza. */
    public function contratar(): int
    {
        $resultado = self::call_sp('sp_contratar_poliza', [
            $this->usuario_id,
            $this->seguro_id,
            $this->catalogo_vehiculo_id,
            $this->placas,
            $this->fecha_inicio,
            $this->fecha_fin,
        ]);

        $this->id = (int) ($resultado[0]['id'] ?? 0);
        return $this->id;
    }
}
