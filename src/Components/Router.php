<?php

namespace App\Components;

use App\Components\Request;
use App\Controllers\BaseController;

/**
 * Компонент маршрутизации
 */
class Router
{
    /**
     * Маршруты
     */
    private $routes = [];

    /**
     * Конструктор
     */
    public function __construct()
    {
        $this->routes = include_once ROOT . '/config/routes.php';
    }

    /**
     * Запускает маршрутизацию
     */
    public function run()
    {
        $uri = Request::getUri();

        foreach ($this->routes as $uriPattern => $path) {
            if (preg_match("%$uriPattern%", $uri)) {
                $internalRoute = preg_replace("%$uriPattern%", $path[0], $uri);
                $segments = explode('/', $internalRoute);

                $lastSegParams = explode('?', $segments[array_key_last($segments)]);
                $segments[array_key_last($segments)] = $lastSegParams[0];

                $name = ucfirst(array_shift($segments));
                $controllerName = 'App\\Controllers\\' . $name . 'Controller';
                $actionName = 'action' . ucfirst(array_shift($segments));

                if (method_exists($controllerName, $actionName)) {
                    $parameters = $segments;

                    $controllerObject = new $controllerName();
                    $controllerObject->beforeAction();

                    if (isset($path[1])) {
                        $controllerObject->checkAuthUser();
                    }

                    call_user_func_array([$controllerObject, $actionName], $parameters);
                    break;
                } else {
                    $controllerObject = new BaseController();
                    $controllerObject->beforeAction();
                    $controllerObject->actionError(404);
                    break;
                }
            }
        }
    }
}
