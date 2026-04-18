<?php

namespace Controllers;

use Model\Usuario;
use MVC\Router;

class PaginasController
{
    public static function landingPage(Router $router): void
    {
        $router->render('paginas/landingPage', []);
    }

    public static function register(Router $router): void
    {
        $errores = [];
        $exito   = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $usuario->password_confirm = $_POST['confirmPassword'] ?? '';

            $errores = $usuario->validar();

            if (!$errores) {
                $usuario->registrar();
                $exito = 'Usuario registrado correctamente. Ya puedes iniciar sesión.';
            }
        }

        $router->render('paginas/register', [
            'errores' => $errores,
            'exito'   => $exito
        ]);
    }

    public static function login(Router $router): void
    {
        $errores     = [];
        $exito       = '';
        $redirectUrl = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email']    ?? '');
            $password = $_POST['password']      ?? '';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = 'El correo electrónico no es válido.';
            if ($password === '')                            $errores[] = 'La contraseña es obligatoria.';

            if (!$errores) {
                $usuario = Usuario::login($email);

                if ($usuario && password_verify($password, $usuario['password'])) {
                    $_SESSION['id']     = $usuario['id'];
                    $_SESSION['nombre'] = $usuario['nombre'];
                    $_SESSION['rol_id'] = $usuario['rol_id'];

                    $exito       = 'Inicio de sesión correcto.';
                    $redirectUrl = '/dashboard';
                } else {
                    $errores[] = 'Credenciales inválidas.';
                }
            }
        }

        $router->render('paginas/login', [
            'errores'     => $errores,
            'exito'       => $exito,
            'redirectUrl' => $redirectUrl
        ]);
    }

    public static function logout(Router $router): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }

        session_destroy();
        header('Location: /');
        exit;
    }

    public static function registrarSiniestros(Router $router): void
    {
        $router->render('paginas/registrarSiniestros', []);
    }

    public static function siniestrosAseguradores(Router $router): void
    {
        $router->render('paginas/siniestrosAseguradores', []);
    }

    public static function siniestrosAsegurados(Router $router): void
    {
        $router->render('paginas/siniestrosAsegurados', []);
    }

    public static function siniestrosSupervisores(Router $router): void
    {
        $router->render('paginas/siniestrosSupervisores', []);
    }
}
