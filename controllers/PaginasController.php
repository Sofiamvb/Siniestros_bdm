<?php

namespace Controllers;

use Model\Admin;
use Model\Player;
use Model\Team;
use Model\User;
use MVC\Router;

class PaginasController
{
    public static function landingPage(Router $router)
    {
        $router->render('paginas/landingPage', []);
    }

    public static function register(Router $router)
    {
        $errores = [];
        $exito = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmPassword'] ?? '';

            if ($name === '') {
                $errores[] = 'El nombre es obligatorio.';
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'El correo electronico no es valido.';
            }

            if (strlen($password) < 6) {
                $errores[] = 'La contrasena debe tener al menos 6 caracteres.';
            }

            if ($password !== $confirmPassword) {
                $errores[] = 'Las contrasenas no coinciden.';
            }

            if (!$errores && User::findByEmail($email)) {
                $errores[] = 'Este correo ya esta registrado.';
            }

            if (!$errores) {
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);
                if (User::create($name, $email, $passwordHash)) {
                    $exito = 'Usuario registrado correctamente. Ya puedes iniciar sesion.';
                } else {
                    $errores[] = 'No se pudo registrar el usuario. Intentalo de nuevo.';
                }
            }
        }

        $router->render('paginas/register', [
            'errores' => $errores,
            'exito' => $exito
        ], 'layout-auth');
    }

    public static function registrarSiniestros(Router $router)
    {
        $router->render('paginas/registrarSiniestros', []);
    }

     public static function siniestrosAseguradores(Router $router)
    {
        $router->render('paginas/siniestrosAseguradores', []);
    }

        public static function siniestrosAsegurados(Router $router)
    {
        $router->render('paginas/siniestrosAsegurados', []);
    }

        public static function siniestrosSupervisores(Router $router)
    {
        $router->render('paginas/siniestrosSupervisores', []);
    }


    public static function login(Router $router)
    {
        $errores = [];
        $exito = '';
        $redirectUrl = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'El correo electronico no es valido.';
            }

            if ($password === '') {
                $errores[] = 'La contrasena es obligatoria.';
            }

            if (!$errores) {
                $user = User::findByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    $_SESSION['user_id'] = $user['id_collector'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['rol'] = 'collector';

                    $exito = 'Inicio de sesion correcto.';
                    $redirectUrl = '/home';
                } else {
                    $admin = Admin::find_by_email();

                    if (!$admin || !password_verify($password, $admin['password'])) {
                        $errores[] = 'Credenciales invalidas.';
                    } elseif (($admin['rol'] ?? '') !== 'Admin') {
                        $errores[] = 'Este administrador no tiene un rol valido.';
                    } else {
                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }

                        $_SESSION['user_id'] = $admin['id_admin'];
                        $_SESSION['fullname'] = $admin['fullname'];
                        $_SESSION['rol'] = 'admin';

                        $exito = 'Inicio de sesion correcto.';
                        $redirectUrl = '/panel';
                    }
                }
            }
        }

        $router->render('paginas/login', [
            'errores' => $errores,
            'exito' => $exito,
            'redirectUrl' => $redirectUrl
        ], 'layout-auth');
    }

    public static function logout(Router $router)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
        header('Location: /');
        exit;
    }

    
}
