<?php

namespace Controllers;

use Model\Companias;
use Model\Usuario;
use Model\Asegurado;
use Model\Siniestro;
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

            $usuario = new Asegurado($_POST);
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
                    $redirectUrl = Usuario::redirectPorRol((int) $_SESSION['rol_id']);
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
        // Solo ajustadores (rol_id = 2)
        if (empty($_SESSION['id']) || ($_SESSION['rol_id'] ?? 0) != 2) {
            header('Location: /login');
            exit;
        }

        $errores = [];
        $exito   = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $siniestro = new Siniestro([
                'poliza_id'     => (int) ($_POST['poliza_id']   ?? 0),
                'ajustador_id'  => (int) $_SESSION['id'],
                'fecha_hora'    => $_POST['fecha_hora']          ?? '',
                'ubicacion'     => $_POST['ubicacion']           ?? '',
                'conductor'     => $_POST['conductor']           ?? '',
                'descripcion'   => $_POST['descripcion']         ?? '',
                'presupuesto'   => (float) ($_POST['presupuesto'] ?? 0),
                'suma_asegurada'=> (float) ($_POST['suma_asegurada'] ?? 0),
            ]);

            $errores = $siniestro->validar();

            if (!$errores) {
                $siniestroId = $siniestro->registrar();

                if ($siniestroId) {
                    // Guardar terceros (JSON del hidden input)
                    $tercerosJson = $_POST['terceros_json'] ?? '[]';
                    $terceros     = json_decode($tercerosJson, true) ?: [];
                    foreach ($terceros as $t) {
                        $siniestro->registrarTercero($siniestroId, $t);
                    }

                    // Guardar evidencias (imágenes/videos)
                    if (!empty($_FILES['evidencias']['name'][0])) {
                        foreach ($_FILES['evidencias']['tmp_name'] as $i => $tmpName) {
                            if ($_FILES['evidencias']['error'][$i] !== UPLOAD_ERR_OK) continue;
                            $binario = file_get_contents($tmpName);
                            $nombre  = $_FILES['evidencias']['name'][$i];
                            $mime    = $_FILES['evidencias']['type'][$i];
                            $siniestro->registrarEvidencia($siniestroId, $binario, $nombre, $mime);
                        }
                    }

                    header('Location: /siniestrosAjustadores?siniestro_nuevo=1');
                    exit;
                }

                $errores[] = 'Ocurrió un error al registrar el siniestro.';
            }
        }

        $router->render('paginas/registrarSiniestros', [
            'errores' => $errores,
            'exito'   => $exito,
        ]);
    }

    public static function apiValidarPoliza(Router $router): void
    {
        header('Content-Type: application/json');
        $numero = trim($_GET['numero'] ?? '');

        if (!$numero) {
            echo json_encode(['error' => 'Número de póliza requerido']);
            exit;
        }

        $poliza = Siniestro::validarPoliza($numero);

        if (!$poliza) {
            echo json_encode(['error' => 'Póliza no encontrada, no activa o no pertenece a la aseguradora seleccionada']);
            exit;
        }

        echo json_encode($poliza);
        exit;
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

    public static function accessRegisterSupervisores(Router $router) : void
    {
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
         }
    }
}
