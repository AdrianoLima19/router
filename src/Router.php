<?php

namespace SurerLoki\Router;

class Router extends Request
{
    use Core;
    // use Execute;

    public function __construct($url)
    {
        parent::__construct($url);
    }

    public function route($route)
    {
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
}
