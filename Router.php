<?php
namespace MVC;

class Router {
    public $rutasGET  = [];
    public $rutasPOST = [];

    public function get($url, $fn) {
        $this->rutasGET[$url] = $fn;
    }

    public function post($url, $fn) {
        $this->rutasPOST[$url] = $fn;
    }

    public function comprobarRutas() {
        $rol_id = $_SESSION['rol_id'] ?? null;

        // Rutas protegidas por rol
        $rutas_asegurados   = [
            '/siniestrosAsegurados',
        ];

        $rutas_ajustadores  = [
            '/siniestrosAjustadores',
            '/registrarSiniestros',
        ];

        $rutas_supervisores = [
            '/siniestrosSupervisores',
            '/register/ajustadores',
            '/siniestro/estado',
        ];

        // Rutas que requieren cualquier rol autenticado
        $rutas_autenticadas = [
            '/buscadorSiniestros',
            '/api/buscar-siniestros',
            '/siniestro',
            '/api/chat',
            '/api/chat/enviar',
        ];

        $urlActual = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $metodo    = $_SERVER['REQUEST_METHOD'];

        if ($metodo === 'GET')  $fn = $this->rutasGET[$urlActual]  ?? null;
        if ($metodo === 'POST') $fn = $this->rutasPOST[$urlActual] ?? null;

        // Verificar acceso por rol — elseif garantiza que solo se evalúa
        // el array al que pertenece la URL, evitando falsos rechazos cruzados
        if (in_array($urlActual, $rutas_asegurados) && $rol_id !== 1) {
            header('Location: /login'); exit;
        } elseif (in_array($urlActual, $rutas_ajustadores) && $rol_id !== 2) {
            header('Location: /login'); exit;
        } elseif (in_array($urlActual, $rutas_supervisores) && $rol_id !== 3) {
            header('Location: /login'); exit;
        } elseif (in_array($urlActual, $rutas_autenticadas) && !in_array($rol_id, [1, 2, 3])) {
            header('Location: /login'); exit;
        }

        if ($fn) {
            call_user_func($fn, $this);
        } else {
            echo "Página No Encontrada";
        }
    }

    public function render($view, $datos = []) {
        foreach ($datos as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean();

        include __DIR__ . "/views/layout.php";
    }
}
