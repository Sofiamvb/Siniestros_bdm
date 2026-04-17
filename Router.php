<?php
namespace MVC;
class Router {
    public $rutasGET = [];
    public $rutasPOST = [];
    public function get($url, $fn){
        $this->rutasGET[$url] = $fn;
    }
    public function post($url, $fn){
        $this->rutasPOST[$url] = $fn;
    }
    public function comprobarRutas(){
        
        $auth = $_SESSION['login'] ?? null;
        //Arreglo de rutas protegidas
        $rutas_protegidas = [];

        $urlActual = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $metodo = $_SERVER['REQUEST_METHOD'];
        if($metodo === 'GET'){
            $fn = $this->rutasGET[$urlActual] ?? null;
        }
        if($metodo === 'POST'){
            $fn = $this->rutasPOST[$urlActual] ?? null;
        }
        //Proteger las rutas
        if(in_array($urlActual, $rutas_protegidas) && !$auth){
            header('Location: /');
        }
        if($fn){
            //La URL existe y hay una funcion asociada
            //Toma la funcion de la variable y la manda a llamar
            call_user_func($fn, $this);
        }else{
            echo "Pagina No Encontrada";
        }
    }

    public function render($view, $datos = []){
        foreach($datos as $key => $value){
            $$key = $value;
        }
        ob_start();
        include __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean();

        include __DIR__ . "/views/layout.php";
    }
}