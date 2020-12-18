<?php

class homeController extends Controller
{
    public function index($params)
    {
        $this->loadTemplate('home');
    }
}
