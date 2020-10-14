<?php

namespace SurerLoki\Router;

class Dispatch
{
    private $fallback;
    private $error;

    public function execute()
    {
        if (empty($this->routes)) {

            $this->error = Router::NOT_FOUND;

            if (!empty($this->fallback)) {
                $this->runFallback();
            }
            return;
        }

        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $route => $value) {
                $route = trim($route, '/');

                if (preg_match("~^" . $route . "$~", $this->request->getUri()) && $value['method'] == $this->request->getHttpMethod()) {
                    $runRoute = $value;
                    break;
                } elseif (preg_match("~^" . $route . "$~", $this->request->getUri())) {
                    $routeExists = true;
                }
            }
        }

        if (!empty($routeExists) && empty($runRoute)) {

            $this->error = Router::METHOD_NOT_ALLOWED;

            if (!empty($this->fallback) && !empty($this->error)) {
                $this->runFallback();
            }

            return;
        }

        if (!empty($runRoute)) {
            if (!empty($runRoute['before']['handler'])) {

                $this->dispatch($runRoute['before']['handler'], $runRoute['before']['action']);
            }

            $this->dispatch($runRoute['handler'], $runRoute['action'], $runRoute['data']);

            if (!empty($runRoute['after']['handler'])) {

                $this->dispatch($runRoute['after']['handler'], $runRoute['after']['action']);
            }

            return;
        }

        $this->error = Router::NOT_FOUND;

        if (!empty($this->fallback) && !empty($this->error)) {
            $this->runFallback();
        }
    }

    /**
     * @param string|callable $handler
     * @param string|null $action
     */
    private function dispatch($handler, $action = null, $data = [])
    {
        if (is_callable($handler)) {
            call_user_func($handler, $data);
            return;
        }

        if (class_exists($handler)) {

            $newHandler = new $handler($this);

            if (method_exists($handler, $action)) {

                $newHandler->$action($data);
            } else {

                $this->error = Router::NOT_IMPLEMENTED;
            }
        } else {

            $this->error = Router::NOT_IMPLEMENTED;
        }

        if (!empty($this->fallback) && !empty($this->error)) {
            $this->runFallback();
        }
    }

    public function fallback($handler)
    {
        if (is_string($handler)) {
            $this->fallback['handler'] = explode(":", $handler)[0];
            $this->fallback['action'] = explode(":", $handler)[1] ?? null;
            return;
        }

        $this->fallback['handler'] = $handler;
        $this->fallback['action'] = null;
    }

    public function runFallback()
    {
        if (is_string($this->fallback['handler']) && !class_exists($this->fallback['handler'])) {
            http_response_code(500);
            throw new \Exception("Error Processing Fallback Class", 500);
        }

        http_response_code($this->error ?? '404');
        $this->dispatch($this->fallback['handler'], $this->fallback['action'], ['error' => $this->error ?? '404']);
        exit;
    }

    public function redirect()
    {
        //
    }

    public function route()
    {
        //
    }
}
