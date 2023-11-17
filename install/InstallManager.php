<?php

require 'InstallController.php';

class InstallManager
{
    private $route;
    private $data;
    private $controller;

    public function __construct()
    {
        $this->controller = new InstallController();
    }
    
    public function run()
    {
        $this->resolveRoute();
        if (empty($this->route['function']) || !method_exists($this->controller, $this->route['function'])) {
            http_response_code(404);
            die;
        }
        $this->resolveData();
        $this->returnAsJson($this->controller->{$this->route['function']}($this->data));
    }

    private function resolveRoute()
    {
        $path = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], 'api/'));
        $path = rtrim($path, '/');
        include 'routes.php';
        $this->route = current(array_filter($routes, function ($r) use ($path) {
            return $r['path'] === $path && $r['method'] === $_SERVER['REQUEST_METHOD'];
        }));
    }

    private function resolveData()
    {
        $this->data = json_decode(file_get_contents('php://input'), true);
    }

    private function returnAsJson($data)
    {
        if (!empty($data)) {
            echo json_encode($data);
        } else {
            echo '';
        }
    }
}
