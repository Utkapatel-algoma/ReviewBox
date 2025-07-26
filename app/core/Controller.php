<?php

class Controller {

    public function __construct() {
        // Add shared logic here if needed
    }

    public function model($model) {
        require_once 'app/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        require_once 'app/views/' . $view . '.php';
    }
}
