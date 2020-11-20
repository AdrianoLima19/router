<?php

namespace SurerLoki\Router;

/**
 * @author Adriano Lima de Souza <surerloki3379@gmail.com>
 * @license MIT
 * @package library
 */
class Core extends Dispatch
{
    /** @var \SurerLoki\Router\Request */
    protected $request;

    /** @var object */
    protected $requestParams;

    /** @var array */
    protected $routes;

    /**
     * @param string|null $baseUrl
     */
    public function __construct($baseUrl = null)
    {
        $this->request = new Request($baseUrl);
        $this->requestParams = $this->request->getRequest();
    }

    /**
     * @param array $methods
     * @param string $route
     * @param string|callable $handler
     * @param array $params
     * @return void
     */
    protected function newRoute($methods, $route, $handler, $params): void
    {
        $route = (trim($route, '/') != '/') ? trim($route, '/') : "";
        $route = (!empty($params['group'])) ? "/{$params['group']}/{$route}" : "/{$route}";
        $route = rtrim($route, '/') != '' ? rtrim($route, '/') : '/';

        $parameters = $this->parseParameters($route, $this->requestParams->uri, $params['regex']);

        $pregRoute = rtrim(preg_replace('~{([^}]*)}~', "([^/]+)", $route), '/') != '' ? rtrim(preg_replace('~{([^}]*)}~', "([^/]+)", $route), '/') : '/';

        array_map(function ($self) use ($route, $handler, $params, $parameters, $pregRoute) {
            $this->routes[$self][$pregRoute] = [
                "route" => $route,
                "method" => $self,
                "before" => $this->parseMiddleware($params['before'], $params['middleware']),
                "after" => $this->parseMiddleware($params['after'], $params['middleware']),
                "handler" => $this->handler($handler, $params['namespace']),
                "action" => $this->action($handler),
                "parameters" => $parameters
            ];
        }, $methods);
    }

    /**
     * @param string $route
     * @param string|null $uri
     * @param string|null $regex
     * @return array
     */
    private function parseParameters($route, $uri, $regex): array
    {
        preg_match_all('/\{\s*([a-zA-Z0-9_-]*)\}/', $route, $brackets, PREG_SET_ORDER);
        $data = array_values(array_diff(explode("/", trim($uri, '/')), explode("/", trim($route, '/'))));

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
    private function action($handler): ?string
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
    private function parseMiddleware($middleware, $namespace): array
    {
        if (is_string($middleware)) {

            return ["handler" => "{$namespace}\\" . explode(":", $middleware)[0] ?? $middleware, "action" => explode(":", $middleware)[1] ?? null];
        }

        return ["handler" => $middleware ?? null, "action" => null];
    }
}
