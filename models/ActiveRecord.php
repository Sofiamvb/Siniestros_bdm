<?php

namespace Model;

use mysqli;

class ActiveRecord
{
    protected static ?mysqli $db = null;
    
    protected static array $errores = [];

    public static function setDB(mysqli $database): void
    {
        static::$db = $database;
    }

    protected static function getDB(): mysqli
    {
        if (static::$db === null) {
            throw new \RuntimeException('Sin conexión a BD. Llama a ActiveRecord::setDB() primero.');
        }
        return static::$db;
    }

    /**
     * Ejecuta un Stored Procedure y devuelve los registros resultantes.
     *
     * @param  string $sp     Nombre del SP (ej. 'sp_obtener_usuario')
     * @param  array  $params Valores a pasar al SP en orden posicional.
     *                        Tipos detectados automáticamente:
     *                          int   → 'i'
     *                          float → 'd'
     *                          resto → 's'  (incluye binarios/BLOB)
     * @return array  Filas devueltas por el SP. Vacío si no hay result set.
     */
    public static function call_sp(string $sp, array $params = []): array
    {
        $db = static::getDB();

        $placeholders = count($params) > 0
            ? implode(', ', array_fill(0, count($params), '?'))
            : '';

        $stmt = $db->prepare("CALL {$sp}({$placeholders})");

        if (!empty($params)) {
            $types  = '';
            $values = [];

            foreach ($params as $value) {
                if (is_int($value))   $types .= 'i';
                elseif (is_float($value)) $types .= 'd';
                else                  $types .= 's';

                $values[] = $value;
            }

            $stmt->bind_param($types, ...$values);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $result->free();
        }

        $stmt->close();
        return $rows;
    }
}
