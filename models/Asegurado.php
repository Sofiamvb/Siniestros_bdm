<?php

namespace Model;

class Asegurado extends Usuario
{
    public string $rfc                   = '';
    public string $licencia_conducir     = '';
    public string $direccion_facturacion = '';

    public function __construct(array $args = [])
    {
        parent::__construct($args);
        $this->rfc                   = strtoupper(trim($args['rfc']                   ?? ''));
        $this->licencia_conducir     = trim($args['licencia_conducir']                ?? '');
        $this->direccion_facturacion = trim($args['direccion_facturacion']            ?? '');
        $this->rol_id                = 1; // Siempre asegurado
    }

    public function validar(): array
    {
        $errores = parent::validar();

        if (empty($this->rfc)) {
            $errores[] = 'El RFC es obligatorio.';
        } elseif (!preg_match('/^[A-Z&Ñ]{3,4}\d{6}[A-Z0-9]{3}$/', $this->rfc)) {
            $errores[] = 'El RFC no tiene un formato válido.';
        }

        if (empty($this->licencia_conducir)) {
            $errores[] = 'La licencia de conducir es obligatoria.';
        }

        if (empty($this->direccion_facturacion)) {
            $errores[] = 'La dirección de facturación es obligatoria.';
        }

        return $errores;
    }

    public function registrar(): int
    {
        // 1. Inserta en usuarios y obtiene el nuevo id
        $usuarioId = parent::registrar();

        if (!$usuarioId) return 0;

        // 2. Inserta el perfil del asegurado
        self::call_sp('sp_registrar_perfil_asegurado', [
            $usuarioId,
            $this->rfc,
            $this->licencia_conducir,
            $this->direccion_facturacion,
        ]);

        return $usuarioId;
    }
}
