<?php

namespace Model;

class Seguro extends ActiveRecord
{
    public int    $id                    = 0;
    public int    $compania_id           = 0;
    public string $nombre_seguro         = '';
    public string $nivel                 = '';
    public string $suma_asegurada        = '';
    public string $deducible_porcentaje  = '';
    public float  $factor_prima          = 0.05; // Viene de la BD
    public string $descripcion_cobertura = '';
    public string $compania              = ''; // JOIN con companias_seguros

    /**
     * Retorna todos los seguros disponibles con datos de la compañía.
     * @return self[]
     */
    public static function obtenerDisponibles(int $vehiculoId): array
    {
        $filas = self::call_sp('sp_get_seguros_disponibles', [$vehiculoId]);

        return array_map(function (array $fila) {
            $s = new self();
            foreach ($fila as $campo => $valor) {
                if (property_exists($s, $campo)) $s->$campo = $valor;
            }
            return $s;
        }, $filas);
    }

    /** Retorna un Seguro por su id o null si no existe. */
    public static function obtenerPorId(int $id): ?self
    {
        $filas = self::call_sp('sp_get_seguro_by_id', [$id]);
        if (empty($filas)) return null;

        $s = new self();
        foreach ($filas[0] as $campo => $valor) {
            if (property_exists($s, $campo)) $s->$campo = $valor;
        }
        return $s;
    }

    /** Calcula la prima anual: valor_vehiculo * factor_prima de la BD. */
    public function calcularPrima(float $valorVehiculo): float
    {
        return round($valorVehiculo * $this->factor_prima, 2);
    }
}
