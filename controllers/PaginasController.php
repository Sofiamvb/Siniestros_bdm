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
            error_log('[register] POST recibido: ' . json_encode(array_keys($_POST)));
            error_log('[register] FILES recibido: ' . json_encode($_FILES));

            $usuario = new Usuario($_POST);
            $usuario->password_confirm = $_POST['confirmPassword'] ?? '';

            // Los archivos vienen en $_FILES, no en $_POST
            $fotoError = $_FILES['foto']['error'] ?? UPLOAD_ERR_NO_FILE;
            error_log('[register] foto error code: ' . $fotoError);

            if ($fotoError === UPLOAD_ERR_OK) {
                $usuario->foto = file_get_contents($_FILES['foto']['tmp_name']);
                error_log('[register] foto cargada, tamaño bytes: ' . strlen($usuario->foto));
            } else {
                error_log('[register] foto NO recibida, código de error: ' . $fotoError);
            }

            $errores = $usuario->validar();
            error_log('[register] errores de validación: ' . json_encode($errores));

            if (!$errores) {
                $usuario->registrar();
                error_log('[register] usuario registrado correctamente');
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
            $usuario  = new Usuario($_POST);
            $errores  = $usuario->validarLogin();

            if (!$errores) {
                $errores = $usuario->verificarPassword($usuario->password);

                if (!$errores) {
                    $redirectUrl = '/dashboard';
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

    public static function siniestrosAjustadores(Router $router): void
    {
        $router->render('paginas/siniestrosAjustadores', []);
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
