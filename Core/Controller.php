<?php

class Controller
{

    public $data;

    public function __construct()
    {
        $this->data = array();
    }

    public function loadTemplate($viewName, $data = array())
    {
        $this->data = $data;

        require '../Views/template.php';
    }

    public function loadView($viewName, $data = array())
    {
        require '../Views/' . $viewName . '.php';
    }
}
