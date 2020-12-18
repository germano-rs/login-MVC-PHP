<?php
class Core
{
    public function __construct()
    {
        $this->run();
    }

    public function run()
    {
        if (isset($_GET['pag'])) {
            $url = $_GET['pag'];
        }
        if (!empty($url)) {
            $params = [];
            $url =  explode('/', $url);
            $controller = $url[0] . 'Controller';
            array_shift($url);

            if (isset($url[0]) && !empty($url[0])) {
                $method = $url[0];
                array_shift($url);
            } else {
                $method = 'index';
            }

            if (count($url) > 0) {
                $params = $url;
            }
        } else {
            $params = [];
            $controller = 'homeController';
            $method = 'index';
        }

        $route = BASE_URL . 'Controller/' . $controller . '.php';

        if (!file_exists($route) && !method_exists($controller, $method)) {
            header("Location:  " . BASE_URL . "Public/404.html");

            die();
        }
        $c = new $controller;
        $c->{$method}($params);
    }
}
