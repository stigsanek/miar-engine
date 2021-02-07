<?php

namespace App\Components;

use App\Components\Request;
use App\Controllers\BaseController;

/**
 * Routing component
 */
class Router
{
    /**
     * Routes
     * @var array
     */
    private $routes = [];

    /**
     * Constructor
     * @param array $routes - routes
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Starts routing
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
     * Prepares route segments
     * @param string $uri - request URI
     * @param string $uriPattern - route URI
     * @param string $action - action with parameters
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
