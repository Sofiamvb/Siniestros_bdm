<?php

namespace Model;

class Supervisor extends Usuario
{
    // Atributos extra de perfiles_empleados
    public $numero_empleado;
    public $zona_cobertura;

    public function __construct($args = [])
    {
        parent::__construct($args);
        $this->numero_empleado = $args['numero_empleado'] ?? '';
        $this->zona_cobertura  = $args['zona_cobertura']  ?? '';
        $this->rol_id          = 3; // Siempre Supervisor
    }

    /**
     * Valida que el token del POST coincida con SUPERVISOR_TOKEN del .env
     * y que la sesión de acceso esté activa.
     */
    public static function verificarAccesoToken(string $token): array
    {
        self::$errores = [];

        if ($token === '') {
            self::$errores[] = 'El token es obligatorio.';
        } elseif ($token !== ($_ENV['SUPERVISOR_TOKEN'] ?? '')) {
            self::$errores[] = 'Token incorrecto.';
        }

        return self::$errores;
    }

    public static function sesionActiva(): bool
    {
        return !empty($_SESSION['supervisor_acceso']);
    }

    public static function verificarAcceso(): void
    {
        if (!self::sesionActiva()) {
            header('Location: /acceso-supervisores');
            exit;
        }
    }
}
