<?php

namespace Bobyblog;

use Bobyblog\Controller\AuthenticationController;

class Router {

    /**
     * Parse Uri of type/ControllerName/ActionName/param1/param2/...
     *
     * Call controller and print its response.
     *
     * Default controller is AuthenticationController
     * Default action is index
     *
     */
    public static function handleRequest() {
        $request = self::parseUri();
        if (!empty($request)) {

            $controllerName = $request['controller'];
            $fullQualifiedClassName = self::getControllerFullQualifiedName($controllerName);
            $action = $request['action'];
            $params = $request['params'];

            // class_exists()  will call the autoloader if class not already loaded.
            if (class_exists($fullQualifiedClassName, true)) {
                if (method_exists($fullQualifiedClassName, $action)) {

                    if (AuthenticationController::hasAccess($fullQualifiedClassName, $action)) {
                        $controller = new $fullQualifiedClassName();
                        echo call_user_func([$controller, $action], $params);
                        return;
                    } else {
                        echo "Login required. ";
                    }
                } else {
                    echo "Method $action not found. ";
                }
            } else {
                echo "Controler $controllerName not found. ";
            }

            http_response_code(404);
            echo "Error 404: Page not found.";
        }
    }

    private static function parseUri() {
        $path = explode('/', filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_SPECIAL_CHARS));
        $params = [];

        // If there are params in the url, after the action
        if (count($path) > 3) {
            // starting from 3 to skip Controller name and Action name
            for ($i = 3; $i < count($path); $i++) {
                $params[] = $path[$i];
            }
        }

        // Get-parameter on Homepage
        if(isset($path[1]) && isset($path[1][0]) && $path[1][0] === '?'){
            $path = [];
        }
        // Default Controller and action are Blog and index
        $request = [
            'controller' => empty($path[1]) ? 'Blog' : ucfirst($path[1]),
            'action' => empty($path[2]) ? 'index' : $path[2],
            'params' => $params
        ];

        return $request;
    }

    private static function getControllerFullQualifiedName($controllerName) {
        return "Bobyblog\\Controller\\{$controllerName}Controller";
    }

    public static function generateUrl($controllerName, $action, ...$params) {
        $fullQualifiedClassName = self::getControllerFullQualifiedName($controllerName);
        if (class_exists($fullQualifiedClassName, true) && method_exists($fullQualifiedClassName, $action)) {
            $urlString = "/{$controllerName}/$action";
            foreach ($params as $param) {
                $urlString .= '/' . $param;
            }
            return $urlString;
        }
        return null;
    }

}
