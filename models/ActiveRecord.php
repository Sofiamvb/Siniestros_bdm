<?php

namespace Model;

use mysqli;

class ActiveRecord
{
    protected static ?mysqli $db = null;

    public static function setDB(mysqli $database): void
    {
        static::$db = $database;
    }

    protected static function getDB(): mysqli
    {
        if (static::$db === null) {
            throw new \RuntimeException('Database connection not configured in ActiveRecord::setDB()');
        }

        return static::$db;
    }

    public function call_sp($store_procedure, $params)
    {
        $query = " CALL " . $store_procedure . " (";
        $query .= join(', ', array_keys($params));    
        $query .= " ) ";
    }
}
