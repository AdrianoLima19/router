<?php

namespace SurerLoki\Router;

abstract class Dispatch
{
    public function run()
    {
        if (empty($this->routes)) {
            $this->error = Router::NOT_FOUND;
            return;
        }

        if (empty($this->routes[$this->httpMethod])) {
            $this->error = Router::METHOD_NOT_ALLOWED;
            return;
        }

        foreach ($this->routes[$this->httpMethod] as $route => $params) {
            if (preg_match("~^" . trim($route, '/') . "$~", $this->requestRoute)) {
                $runRoute = $params;
            }
        }

        if (!empty($runRoute)) {

            if (!empty($this->middlewares[$this->httpMethod])) {

                foreach ($this->middlewares[$this->httpMethod] as $middleware => $params) {
                    if (preg_match("~^" . trim($middleware, '/') . "$~", $this->requestRoute)) {
                        $runMiddleware = $params;
                    }
                }

                if (!empty($runMiddleware)) {

                    $middleware = $runMiddleware['handler'];
                    $method = $runMiddleware['action'] ?? null;

                    if (is_callable($middleware)) {
                        call_user_func($middleware);
                    } else {
                        if (class_exists($middleware)) {
                            $middleware = new $middleware($this);

                            if (method_exists($middleware, $method)) {
                                $middleware->$method();
                            } else {
                                $this->error = Router::NOT_IMPLEMENTED;
                                return;
                            }
                        }
                    }
                }
            }

            if (is_callable($runRoute['handler'])) {
                call_user_func($runRoute['handler'], ($runRoute['data'] ?? []));
                return;
            }

            $controller = $runRoute['handler'];
            $method = $runRoute['action'] ?? null;

            if (class_exists($controller)) {
                $newController = new $controller($this);

                if (method_exists($controller, $method)) {
                    $newController->$method(($runRoute['data'] ?? []));
                } else {
                    $this->error = Router::NOT_IMPLEMENTED;
                }
                return;
            }

            $this->error = Router::BAD_REQUEST;
            return;
        }

        $this->error = Router::NOT_FOUND;
        return;
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
        return $this->routes;
    }
}
