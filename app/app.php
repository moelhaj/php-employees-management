<?php

require_once '../vendor/autoload.php';
require_once 'core/database.php';

define('ENVIRONMENT', 'development');

if (ENVIRONMENT == 'development' || ENVIRONMENT == 'dev') {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}


define('URL_PUBLIC_FOLDER', 'public');
define('URL_PROTOCOL', '//');
define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);

define('DIR', __DIR__ );
define('DS', DIRECTORY_SEPARATOR );

spl_autoload_register(function($class) {
    if(file_exists(DIR . DS . $class . '.php')){
        require_once(DIR . DS . $class . '.php'); 
    } else {
        $folders = array('controllers', 'core', 'models');
        foreach ($folders as $folder) {
            if (file_exists(DIR . DS . $folder . DS . $class . '.php')) {
                require_once (DIR . DS . $folder . DS . $class . '.php');
            }
        } 
    }
});

class App
{
    private $controller = null;
    private $action = null;
    private $params = array();

    public function __construct()
    {
        $this->splitUrl();

        if (!$this->controller) {
            Home::index();
        } elseif (file_exists( DIR . DS. 'controllers' . DS . $this->controller . '.php')) {
            if (method_exists($this->controller, $this->action) && !empty($this->params)) {
                call_user_func_array(array($this->controller, $this->action), $this->params);
            } elseif (method_exists($this->controller, $this->action) && empty($this->params)) {
                $this->controller::{$this->action}();
            } elseif(strlen($this->action) == 0){
                 $this->controller::index();
            } elseif(!method_exists($this->controller, $this->action)){
                NotFound::error();
            } elseif(!method_exists($this->controller, $this->action)){
                NotFound::error();
            } else {
                NotFound::error();
            }

        } else {
            NotFound::error();
        }

    }

    private function splitUrl()
    {
        if (isset($_GET['url'])) {

            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);

            $this->controller = isset($url[0]) ? $url[0] : null;
            $this->controller = ucfirst($this->controller);
            $this->action = isset($url[1]) ? $url[1] : null;

            unset($url[0], $url[1]);

            $this->params = array_values($url);

        }
    }
}