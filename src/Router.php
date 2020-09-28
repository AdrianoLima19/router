<?php

namespace SurerLoki\Router;

class Router extends Request
{
    use Core;
    // TODO use Dispatch;

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
     */
    public function all($route, $handler)
    {
        $this->error = self::NOT_IMPLEMENTED;
    }

    /**
     * @param string|array $method
     * @param string $route
     * @param string|callable $handler
     */
    public function route($method, $route, $handler)
    {
        $this->error = self::NOT_IMPLEMENTED;
    }

    /**
     * @param string $route
     * @param string|callable $handler
     */
    public function get($route, $handler)
    {
        $this->newRoute('GET', $route, $handler);
    }

    /**
     * @param string $route
     * @param string|callable $handler
     */
    public function post($route, $handler)
    {
        $this->newRoute('POST', $route, $handler);
    }

    /**
     * @param string $route
     * @param string|callable $handler
     */
    public function put($route, $handler)
    {
        $this->newRoute('PUT', $route, $handler);
    }

    /**
     * @param string $route
     * @param string|callable $handler
     */
    public function patch($route, $handler)
    {
        $this->newRoute('PATCH', $route, $handler);
    }

    /**
     * @param string $route
     * @param string|callable $handler
     */
    public function delete($route, $handler)
    {
        $this->newRoute('DELETE', $route, $handler);
    }
}
