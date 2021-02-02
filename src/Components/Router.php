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
     * @var array
     */
    private $routes = [];

    /**
     * Конструктор
     * @param array $routes - маршруты
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Запускает маршрутизацию
     */
    public function run()
    {
        $uri = Request::getUri();

        foreach ($this->routes as $uriPattern => $path) {
            if (preg_match("%$uriPattern%", $uri)) {
                list($class, $action, $isRequireAuth) = $path;

                $segments = $this->prepareSegments($uri, $uriPattern, $action);
                $method = array_shift($segments);

                if (method_exists($class, $method)) {
                    $params = $segments;

                    $controllerObject = new $class();
                    $controllerObject->beforeAction();

                    if ($isRequireAuth) {
                        $controllerObject->checkAuthUser();
                    }

                    call_user_func_array([$controllerObject, $method], $params);
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

    /**
     * Подготавливает сегменты маршрута
     * @param string $uri - URI запроса
     * @param string $uriPattern - URI маршрута
     * @param string $action - action с параметрами
     * @return array
     */
    private function prepareSegments(string $uri, string $uriPattern, string $action)
    {
        $internalRoute = preg_replace("%$uriPattern%", $action, $uri);
        $segments = explode('/', $internalRoute);

        $lastSegParams = explode('?', $segments[array_key_last($segments)]);
        $segments[array_key_last($segments)] = array_shift($lastSegParams);

        return $segments;
    }
}
