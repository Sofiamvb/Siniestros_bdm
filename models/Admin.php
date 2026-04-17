<?php

namespace Model;

class Admin extends ActiveRecord
{
    // Definicion en base de datos
    protected static $tabla = 'admin';
    protected static $columnasDB = ['id', 'email', 'password', 'birtday'];

    public $id;
    public $email;
    public $password;
    public $birthday;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password = $args['birthday'] ?? '';
    }


    public static function find_by_email()
    {
        $db = static::getDB();
        $sql = 'SELECT id_admin, rol, fullname, email, password FROM Administrador WHERE email = ? LIMIT 1';
        $stmt = $db->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();

        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        $stmt->close();
        return $admin ?: null;
    }
}
