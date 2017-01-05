<?php

class Route
{
    static function start()
    {
        $url = explode('?', $_SERVER['REQUEST_URI']);
        $routes = explode('/', $url[0]);
        $controller_suffix = !empty($routes[1]) ? $routes[1] : 'catalogue';
        $action_suffix = !empty($routes[2]) ? $routes[2] : 'index';
        $controller_name = 'Controller' . ucfirst($controller_suffix);
        $controller_path = "application/controllers/controller_" . $controller_suffix . '.php';
        $model_path = "application/models/model_" . $controller_suffix . '.php';

        $loggedIn = isset($_SESSION['user_connected']) && $_SESSION['user_connected'] === true;
        if (!$loggedIn and $controller_name != 'ControllerLogin') {
            header("Location: /login");
        } else if (file_exists($controller_path)) {
            include $controller_path;
            include $model_path;
            $controller = new $controller_name;
            $action = 'action_' . $action_suffix;
            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                http_response_code(404);
            }
        } else {
            http_response_code(404);
        }
    }
}