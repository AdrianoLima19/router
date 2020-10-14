<?php

namespace SurerLoki\Router;

class Core extends Dispatch
{
    /** @var SurerLoki\Router\Request */
    protected $request;
    protected $routes;

    /**
     * @param string $url
     */
    public function __construct($url = null)
    {
        $this->request = new Request($url);
        // ? implement cache
    }

    /**
     * @param array $methods
     * @param string $route
     * @param string|callable $handler
     * @param array|null $next
     */
    protected function newRoute($methods, $route, $handler, $optionalParams)
    {
        $route = (trim($route, '/') != '/') ? trim($route, '/') : "";
        $route = (!empty($optionalParams['group'])) ? "/{$optionalParams['group']}/{$route}" : "/{$route}";

        $parameters = $this->parseParameters($route, $this->request->getUri(), $optionalParams['regex']);

        $data = array_merge($parameters ?? [], $this->request->getRequest() ?? []);

        $pregRoute = preg_replace('~{([^}]*)}~', "([^/]+)", $route);

        array_map(function ($self) use ($route, $handler, $optionalParams, $data, $pregRoute) {
            $this->routes[$self][$pregRoute] = [
                "route" => $route,
                "method" => $self,
                "before" => $this->middleware($optionalParams['before']),
                "after" => $this->middleware($optionalParams['after']),
                "handler" => $this->handler($handler, $optionalParams['namespace']),
                "action" => $this->action($handler),
                "data" => $data
            ];
        }, $methods);
    }

    /**
     * @param string $route
     * @param string|null $uri
     * @param string|null $regex
     * @return array|null
     */
    private function parseParameters($route, $uri, $regex)
    {
        preg_match_all('/\{\s*([a-zA-Z0-9_-]*)\}/', $route, $brackets, PREG_SET_ORDER);
        $data = array_values(array_diff(explode("/", $uri), explode("/", trim($route, '/'))));

        if ($regex) {
            for ($key = 0; $key < count($brackets); $key++) {
                $match = $regex[$brackets[$key][1]] ?? null;
                $keyData = $data[$key] ?? null;

                if (!empty($match)) {
                    preg_match_all("/$match/", $keyData, $pregData);

                    $return[$brackets[$key][1]] = !empty($pregData[0]) ? implode("", $pregData[0]) : null;
                } else {
                    $return[$brackets[$key][1]] = $keyData;
                }
            }

            return $return ?? [];
        }

        for ($key = 0; $key < count($brackets); $key++) {
            $keyData = $data[$key] ?? null;
            $return[$brackets[$key][1]] = $keyData;
        }
        return $return ?? [];
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
     * @param string|callable $middleware
     * @return array
     */
    private function middleware($middleware)
    {
        if (is_string($middleware)) {
            return ["handler" => explode(":", $middleware)[0] ?? null, "action" => explode(":", $middleware)[1] ?? null];
        }
        return ["handler" => $middleware ?? null, "action" => null];
    }
}
