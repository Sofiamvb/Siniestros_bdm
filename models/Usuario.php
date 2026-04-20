<?php

namespace Model;

class Usuario extends ActiveRecord
{
    protected static $tabla = 'usuarios';
    protected static array $errores = [];

    public $id;
    public $nombre;
    public $apellidos;
    public $fecha_nacimiento;
    public $foto;
    public $genero;
    public $email;
    public $password;
    public $alias;
    public $rol_id;

    // Solo para validación en registro, no se guarda en BD
    public $password_confirm;

    public function __construct($args = [])
    {
        $this->id               = $args['id']               ?? null;
        $this->nombre           = $args['nombre']           ?? '';
        $this->apellidos        = $args['apellidos']        ?? '';
        $this->fecha_nacimiento = $args['fecha_nacimiento'] ?? '';
        $this->foto             = $args['foto']             ?? null;
        $this->genero           = $args['genero']           ?? '';
        $this->email            = $args['email']            ?? '';
        $this->password         = $args['password']         ?? '';
        $this->alias            = $args['alias']            ?? '';
        $this->rol_id           = $args['rol_id']           ?? 1; // 1 = Asegurado por defecto
    }

    public function validar(): array
    {
        self::$errores = [];

        if (empty($this->nombre))    self::$errores[] = 'El nombre es obligatorio.';
        if (empty($this->apellidos)) self::$errores[] = 'Los apellidos son obligatorios.';
        if (empty($this->genero))    self::$errores[] = 'El género es obligatorio.';
        if (empty($this->alias))     self::$errores[] = 'El alias es obligatorio.';
        if (empty($this->foto))      self::$errores[] = 'La foto de perfil es obligatoria.';

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores[] = 'El correo electrónico no es válido.';
        }

        if (strlen($this->password) < 6) {
            self::$errores[] = 'La contraseña debe tener al menos 6 caracteres.';
        }
        if ($this->password !== $this->password_confirm) {
            self::$errores[] = 'Las contraseñas no coinciden.';
        }

        if (empty($this->fecha_nacimiento)) {
            self::$errores[] = 'La fecha de nacimiento es obligatoria.';
        } else {
            $edad = (int) date_diff(date_create($this->fecha_nacimiento), date_create('today'))->y;
            if ($edad < 18) self::$errores[] = 'Debes ser mayor de 18 años para registrarte.';
        }

        return self::$errores;
    }

    public function registrar(): int
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);

        $resultado = self::call_sp('sp_registrar_usuario', [
            $this->nombre,
            $this->apellidos,
            $this->fecha_nacimiento,
            $this->genero,
            $this->email,
            $this->password,
            $this->alias,
            (int) $this->rol_id,
            $this->foto
        ]);

        $this->id = (int) ($resultado[0]['id'] ?? 0);
        return $this->id;
    }

    public function validarLogin(): array
    {
        self::$errores = [];

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$errores[] = 'El correo electrónico no es válido.';
        }
        if (empty($this->password)) {
            self::$errores[] = 'La contraseña es obligatoria.';
        }

        return self::$errores;
    }

    public static function login(string $email): ?array
    {
        $rows = self::call_sp('sp_login_usuario', [$email]);
        return $rows[0] ?? null;
    }

    public static function redirectPorRol(int $rol_id): string
    {
        return match($rol_id) {
            2       => '/siniestrosAjustadores',
            3       => '/siniestrosSupervisores',
            default => '/siniestrosAsegurados',   // rol_id = 1 y cualquier otro
        };
    }

    public function verificarPassword(string $passwordIngresado): array
    {
        self::$errores = [];

        $usuario = self::login($this->email);

        if (!$usuario || !password_verify($passwordIngresado, $usuario['password'])) {
            self::$errores[] = 'Credenciales inválidas.';
            return self::$errores;
        }

        // Credenciales correctas — cargar sesión
        $_SESSION['id']     = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['rol_id'] = $usuario['rol_id'];

        // Convertir foto LONGBLOB a data URI para usar en <img src> sin request extra
        if (!empty($usuario['foto'])) {
            $_SESSION['foto'] = 'data:image/jpeg;base64,' . base64_encode($usuario['foto']);
        }

        return self::$errores;
    }
}
