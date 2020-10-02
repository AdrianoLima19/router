<?php

namespace SurerLoki\Router;

abstract class Dispatch
{
    /**
     * @return bool
     */
    public function run()
    {
        if (empty($this->routes) || empty($this->routes[$this->httpMethod])) {
            $this->error = Router::NOT_IMPLEMENTED;
            return false;
        }

        foreach ($this->routes[$this->httpMethod] as $route => $params) {
            if (preg_match("~^" . ltrim($route, '/') . "$~", $this->requestRoute)) {
                $runRoute = $params;
            }
        }

        if (!empty($runRoute)) {
            if (is_callable($runRoute['handler'])) {
                call_user_func($runRoute['handler'], ($runRoute['data'] ?? []));
                return true;
            }

            $controller = $runRoute['handler'];
            $method = $runRoute['action'];

            if (class_exists($controller)) {
                $newController = new $controller($this);
                if (method_exists($controller, $method)) {
                    $newController->$method(($runRoute['data'] ?? []));
                    return true;
                }

                $this->error = Router::METHOD_NOT_ALLOWED;
                return false;
            }

            $this->error = Router::BAD_REQUEST;
            return false;
        }

        $this->error = Router::NOT_FOUND;
        return false;
    }

    public function redirect()
    {
    }

    /**
     * @return null|int
     */
    public function error()
    {
        return $this->error;
    }
}
