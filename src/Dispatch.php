<?php

namespace SurerLoki\Router;

class Dispatch
{
    /** @var callable|string */
    private $fallback;

    /** @var int */
    private $error;

    public function execute()
    {
        if (empty($this->routes)) {

            $this->error = Router::NOT_FOUND;

            if (!empty($this->fallback)) {
                $this->runFallback();
            }

            http_response_code($this->error);
            exit;
        }

        foreach ($this->routes as $method) {
            foreach ($method as $route => $params) {
                $route = trim($route, '/');

                if (preg_match("~^" . $route . "$~", $this->request->getUri())) {
                    $routeExists = true;
                    if ($params['method'] == $this->request->getHttpMethod()) {
                        $runRoute = $params;
                        break;
                    }
                }
            }
        }

        if (!empty($routeExists) && empty($runRoute)) {

            $this->error = Router::METHOD_NOT_ALLOWED;

            if (!empty($this->fallback) && !empty($this->error)) {
                $this->runFallback();
            }

            http_response_code($this->error);
            return;
        }

        if (!empty($runRoute)) {

            if ($runRoute['method'] == 'HEAD') {
                ob_start();
            }

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

        http_response_code($this->error);
        return;
    }

    /**
     * @param string|callable $handler
     * @param string|null $action
     * @param array $data
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

        if (!empty($this->error)) {
            if (!empty($this->fallback)) {
                http_response_code($this->error);
                exit;
            }

            $this->runFallback();
        }
    }

    /**
     * @param string|callable $handler
     */
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

    /**
     * @param string $route
     * @return void
     */
    public function redirect($route)
    {
        if (filter_var($route, FILTER_VALIDATE_URL)) {
            header("Location: {$route}");
            exit;
        }

        $route = '/' . filter_var(trim($route, '/'), FILTER_SANITIZE_SPECIAL_CHARS);
        header("Location: {$route}");

        exit;
    }

    /**
     * @param string $route
     * @param integer $status
     * @return void
     */
    public function route($route, $status = 301)
    {
        $route = filter_var($route, FILTER_SANITIZE_SPECIAL_CHARS);
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = $route;

        $this->request = new Request($this->request->getRootUrl());
        $route = "/{$this->request->getUri()}";


        foreach ($this->routes['GET'] as $routes) {

            if (preg_match("~^" . $routes['route'] . "$~", $route)) {

                http_response_code($status);
                $this->execute();

                exit;
            }
        }

        $this->runFallback();

        exit;
    }
}
