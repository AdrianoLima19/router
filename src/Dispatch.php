<?php

namespace SurerLoki\Router;

abstract class Dispatch
{
    // TODO refactor dispath
    /**
     * @return bool
     */
    public function run()
    {
        if (empty($this->routes)) {
            $this->error = Router::NOT_IMPLEMENTED;
            return false;
        }
        if (empty($this->routes[$this->httpMethod])) {
            $this->error = Router::METHOD_NOT_ALLOWED;
            return false;
        }
        foreach ($this->routes[$this->httpMethod] as $route => $params) {
            if (preg_match("~^" . trim($route, '/') . "$~", $this->requestRoute)) {
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
    // TODO Redirect
    public function redirect()
    {
    }

    /**
     * @return int|null
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * @return string|array|null
     */
    public function routes()
    {
        return $this->routes['GET'];
    }
}
