<?php

namespace Model;

class Companias extends ActiveRecord
{
    public $id;
    public $nombre_comercial;
    public $razon_social;
    public $rfc;
    public $telefono_cabina;
    
    public static function obtenerCompanias(){
        $filas = self::call_sp('sp_get_companias_seguros');
        return $filas;
    }
}