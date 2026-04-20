<?php

namespace Model;

class Ajustador extends Usuario
{
    public $numero_empleado;
    public $zona_cobertura;

    public function __construct($args = [])
    {
        parent::__construct($args);
        $this->numero_empleado = self::generarNumeroEmpleado();
        $this->zona_cobertura  = $args['zona_cobertura'] ?? '';
        $this->rol_id          = 2; // Siempre Ajustador
    }

    private static function generarNumeroEmpleado(): string
    {
        return sprintf(
            'AJU-%s-%s-%s-%s',
            bin2hex(random_bytes(2)),
            bin2hex(random_bytes(2)),
            bin2hex(random_bytes(2)),
            bin2hex(random_bytes(2))
        );
    }

    public function validar(): array
    {
        parent::validar();

        if (empty($this->zona_cobertura)) {
            self::$errores[] = 'La zona de cobertura es obligatoria.';
        }

        return self::$errores;
    }

    public function registrar(): int
    {
        $usuarioId = parent::registrar();

        if (!$usuarioId) return 0;

        self::call_sp('sp_registrar_perfil_empleado', [
            $usuarioId,
            $this->numero_empleado,
            $this->zona_cobertura,
        ]);

        return $usuarioId;
    }

    /**
     * Verifica que quien registra el ajustador sea un Supervisor (rol_id = 3).
     * El Router ya protege la ruta, esto es una segunda capa de seguridad.
     */
    public static function verificarRegistroPorSupervisor(): void
    {
        if (($_SESSION['rol_id'] ?? null) !== 3) {
            header('Location: /login');
            exit;
        }
    }
}
