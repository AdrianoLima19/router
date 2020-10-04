<?php

namespace SurerLoki\Router;

class Core extends Dispatch
{
    use Request;

    protected $data;
    protected $error;
    protected $routes;
    private $namespace;
    private $group;
    private $nested;
    protected $middlewares;
    private $middlewareRoute;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->request($url);
    }

    /**
     * @param string $method
     * @param string $route
     * @param string|callable $handler
     */
    protected function newRoute($method, $route, $handler)
    {
        $route = (trim($route, '/') != '/') ? trim($route, '/') : "";
        $route = (!empty($this->group)) ? "/{$this->group}/{$route}" : "/{$route}";
        $requestRoute = explode("/", $this->requestRoute);
        $urlData = array_values(array_diff($requestRoute, explode("/", rtrim($route, '/'))));

        // ? if in future updates url accept regex parameters verify here
        preg_match_all('/\{\s*([a-zA-Z0-9_-]*)\}/', $route, $keys, PREG_SET_ORDER);

        for ($key = 0; $key < count($keys); $key++) {
            $data[$keys[$key][1]] = ($urlData[$key]) ?? null;
        }
        $data = $data ?? [];
        $pregRoute = preg_replace('~{([^}]*)}~', "([^/]+)", $route);

        $this->middlewareRoute = [
            "method" => $method,
            "route" => $route,
            "pregRoute" => $pregRoute
        ];

        array_map(function ($self) use ($data, $pregRoute, $route, $handler) {
            $this->data = $this->parseData($self, $data);
            $this->routes[$self][$pregRoute] = [
                "route" => $route,
                "method" => $self,
                "handler" => $this->handler($handler, $this->namespace),
                "action" => $this->action($handler),
                "data" => $this->data
            ];
        }, is_array($method) ? $method : (array) $method);
    }

    /**
     * @param string $namespace
     * @param callable|null $callback
     */
    public function namespace($namespace, $callback = null)
    {
        if (!$callback) {
            $this->namespace = $namespace;
            return;
        }

        $keepNamespace = $this->namespace;
        $this->namespace = $namespace;
        $callback($this);
        $this->namespace = $keepNamespace;
    }


    /**
     * @param string $group
     * @param callable|null $callback
     */
    public function group($group, $callback = null)
    {
        $group = (trim($group, '/') != '/' ? trim($group, '/') : '');

        if (!$callback) {
            $this->group = $group;
            return;
        }

        if (empty($this->nested)) {
            $this->nested = $this->group;
            $this->group = $group;
            $callback($this);
            $this->group = $this->nested;
            $this->nested = null;
        } else {
            $this->group .= "/{$group}";
            $callback($this);
        }
    }

    /**
     * @param string|callable $handler
     * @return string|null
     */
    private function action($handler)
    {
        return is_string($handler) ? explode(":", $handler)[1] : null;
    }

    /**
     * @param string|callable $handler
     * @param string $namespace
     * @return string|callable
     */
    private function handler($handler, $namespace)
    {
        return is_string($handler) ? "{$namespace}\\" . explode(":", $handler)[0] : $handler;
    }

    /**
     * @param string|callable $handler
     */
    public function middleware($handler)
    {
        array_map(function ($self) use ($handler) {

            if (is_string($handler)) {
                $handler = explode(":", $handler);
                $action = (!empty($handler[1])) ? $handler[1] : "handler";
                $handler = $handler[0];
            }

            $this->middlewares[$self][$this->middlewareRoute['pregRoute']] = [
                "route" => $this->middlewareRoute['route'],
                "method" => $self,
                "handler" => $handler,
                "action" => isset($action) ? $action : null,
            ];
        }, is_array($this->middlewareRoute['method']) ? $this->middlewareRoute['method'] : (array) $this->middlewareRoute['method']);
    }
}
