<?php

namespace SurerLoki\Router;

final class Router extends Core
{
    public const METHODS = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];
    public const BAD_REQUEST = 400;
    public const NOT_FOUND = 404;
    public const METHOD_NOT_ALLOWED = 405;
    public const NOT_IMPLEMENTED = 501;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        parent::__construct($url);
    }

    /**
     * @param string $route
     * @param string|callable $handler
     * @return SurerLoki\Router\Core
     */
    public function any($route, $handler): Core
    {
        $this->newRoute(self::METHODS, $route, $handler);
        return $this;
    }

    /**
     * @param string|array $method
     * @param string $route
     * @param string|callable $handler
     * @return SurerLoki\Router\Core
     */
    public function route($method, $route, $handler): Core
    {
        $method = array_map('strtoupper', is_string($method) ? (array) $method : $method);

        array_map(function ($self) use ($route, $handler) {
            if (!in_array($self, self::METHODS)) {
                http_response_code(self::METHOD_NOT_ALLOWED);
                throw new \Exception("Method {$self} not allowed", 405);
            }
        }, $method);

        $this->newRoute($method, $route, $handler);
        return $this;
    }

    /**
     * @param string $route
     * @param string|callable $handler
     * @return SurerLoki\Router\Core
     */
    public function get($route, $handler): Core
    {
        $this->newRoute(['GET', 'HEAD'], $route, $handler);
        return $this;
    }

    /**
     * @param string $route
     * @param string|callable $handler
     * @return SurerLoki\Router\Core
     */
    public function post($route, $handler): Core
    {
        $this->newRoute('POST', $route, $handler);
        return $this;
    }

    /**
     * @param string $route
     * @param string|callable $handler
     * @return SurerLoki\Router\Core
     */
    public function put($route, $handler): Core
    {
        $this->newRoute('PUT', $route, $handler);
        return $this;
    }

    /**
     * @param string $route
     * @param string|callable $handler
     * @return SurerLoki\Router\Core
     */
    public function patch($route, $handler): Core
    {
        $this->newRoute('PATCH', $route, $handler);
        return $this;
    }

    /**
     * @param string $route
     * @param string|callable $handler
     * @return SurerLoki\Router\Core
     */
    public function delete($route, $handler): Core
    {
        $this->newRoute('DELETE', $route, $handler);
        return $this;
    }

    /**
     * @param string $route
     * @param string|callable $handler
     * @return SurerLoki\Router\Core
     */
    public function options($route, $handler): Core
    {
        $this->newRoute('OPTIONS', $route, $handler);
        return $this;
    }
}
