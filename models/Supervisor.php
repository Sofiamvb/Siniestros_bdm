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
        $this->numero_empleado = self::generarNumeroEmpleado();
        $this->zona_cobertura  = $args['zona_cobertura'] ?? '';
        $this->rol_id          = 3; // Siempre Supervisor
    }

    private static function generarNumeroEmpleado(): string
    {
        return sprintf(
            'SUP-%s-%s-%s-%s',
            bin2hex(random_bytes(2)),
            bin2hex(random_bytes(2)),
            bin2hex(random_bytes(2)),
            bin2hex(random_bytes(2))
        );
    }

    /**
     * Valida que el token del POST coincida con SUPERVISOR_TOKEN del .env
     * y que la sesión de acceso esté activa.
     */
    public function validar(): array
    {
        parent::validar();

        if (empty($this->zona_cobertura)) {
            self::$errores[] = 'La zona de cobertura es obligatoria.';
        }

        return self::$errores;
    }

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
