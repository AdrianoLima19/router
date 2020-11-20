<?php

namespace SurerLoki\Router;

/**
 * @author Adriano Lima de Souza <surerloki3379@gmail.com>
 * @license MIT
 * @package library
 */
class Dispatch
{
    /** @var array */
    private $fallback;

    /** @var array */
    private $fallbackInfo;

    /** @var int */
    private $error;

    /**
     * @return void
     */
    protected function execute(): void
    {
        if (empty($this->routes)) {

            $this->error = Router::NOT_FOUND;
            $this->runFallback();

            return;
        }

        foreach ($this->routes as $method) {

            foreach ($method as $route => $params) {

                if (preg_match("~^" . $route . "$~", $this->requestParams->uri)) {

                    /** @var bool */
                    $routeExists = true;

                    if ($params['method'] == $this->requestParams->method) {

                        $this->request->setParams($params['parameters']);
                        $this->requestParams = $this->request->getRequest();

                        /** @var array */
                        $runRoute = $params;
                        break;
                    }
                }
            }
        }

        if (!empty($routeExists) && empty($runRoute)) {

            $this->error = Router::METHOD_NOT_ALLOWED;
            $this->runFallback();

            return;
        }

        if (!empty($runRoute)) {

            if (!empty($runRoute['before']['handler'])) {

                $this->dispatch($runRoute['before']['handler'], $runRoute['before']['action']);
            }

            $this->dispatch($runRoute['handler'], $runRoute['action']);

            if (!empty($runRoute['after']['handler'])) {

                $this->dispatch($runRoute['after']['handler'], $runRoute['after']['action']);
            }

            return;
        }

        $this->error = Router::NOT_FOUND;
        $this->runFallback();

        return;
    }

    /**
     * @param string|callable $handler
     * @param string|null $action
     * @return void
     */
    private function dispatch($handler, $action = null): void
    {
        /** @var object */
        $request = $this->requestParams;

        /** @var Response */
        $response = new Response();

        if (is_callable($handler)) {

            call_user_func($handler, $request, $response, $this);

            return;
        }

        if (class_exists($handler)) {

            $newHandler = new $handler($this);

            if (method_exists($handler, $action)) {

                $newHandler->$action($request, $response, $this);
            } else {

                $this->error = Router::NOT_IMPLEMENTED;
            }
        } else {

            $this->error = Router::NOT_IMPLEMENTED;
        }

        if (!empty($this->error)) {

            $this->runFallback();

            return;
        }
    }

    /**
     * @param string|callable $handler
     * @return void
     */
    public function fallback($handler): void
    {
        if (is_string($handler)) {

            $this->fallbackInfo['route'] = "fallback";
            $this->fallbackInfo['handler'] = $this->fallback['handler'] = $this->namespace . '\\' . explode(":", $handler)[0];
            $this->fallbackInfo['action'] = $this->fallback['action'] = explode(":", $handler)[1];

            return;
        }

        $this->fallbackInfo['route'] = "fallback";
        $this->fallbackInfo['handler'] = $this->fallback['handler'] = $handler;
        $this->fallbackInfo['action'] = $this->fallback['action'] = null;
    }

    /**
     * @return void
     */
    private function runFallback(): void
    {
        if (empty($this->fallback)) {

            http_response_code($this->error ?? '404');

            return;
        }

        if (is_string($this->fallback['handler']) && !class_exists($this->fallback['handler'])) {

            http_response_code(500);

            $debug = debug_backtrace();
            $debug = end($debug);

            trigger_error("Error processing fallback class on line {$debug['line']} in {$debug['file']}.", E_USER_ERROR);
        }

        http_response_code($this->error ?? '404');

        $this->requestParams->error =  $this->error ?? '404';

        $this->dispatch(array_shift($this->fallback), array_shift($this->fallback));

        return;
    }

    /**
     * @param string $route
     * @return void
     */
    public function redirect($route): void
    {
        if (filter_var($route, FILTER_VALIDATE_URL)) {

            header("Location: {$route}");

            return;
        }

        $route = '/' . filter_var(trim($route, '/'), FILTER_SANITIZE_SPECIAL_CHARS);

        $root = str_replace($this->requestParams->uri, "", rawurldecode($_SERVER['REQUEST_URI']));
        header("Location: {$root}{$route}");

        return;
    }

    /**
     * @param string $route
     * @param integer $status
     * @return void
     */
    public function route($route, $status = 301): void
    {
        $route = filter_var($route, FILTER_SANITIZE_SPECIAL_CHARS);
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = $route;

        $this->request = new Request($this->requestParams->baseUrl);
        $this->requestParams = $this->request->getRequest();
        $route = "{$this->requestParams->uri}";

        foreach ($this->routes['GET'] as $routes => $params) {

            if (preg_match("~^" . $routes . "$~", $route)) {

                http_response_code($status);
                $this->execute();

                return;
            }
        }

        $this->runFallback();

        return;
    }

    /**
     * @return array|null
     */
    public function list()
    {
        $this->routes['GET']['fallback'] = $this->fallbackInfo ?? null;

        return $this->routes;
    }
}
