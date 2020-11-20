<?php

namespace SurerLoki\Router;

/**
 * @version 2.0.0
 * @author Adriano Lima de Souza <surerloki3379@gmail.com>
 * @license MIT
 * @package library
 */
class Router extends Core
{
    /** @var array */
    private $requestChain;

    /** @var string */
    protected $namespace;

    /** @var string */
    private $middleware;

    /** @var array */
    private $groupChain;

    /** @var string */
    private $compiler;

    /** @var int */
    private $counter = 0;

    /** @var array HTTP verbs */
    public const METHODS = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    /** @var int */
    public const NOT_FOUND = 404;

    /** @var int */
    public const METHOD_NOT_ALLOWED = 405;

    /** @var int */
    public const INTERNAL_ERROR = 500;

    /** @var int */
    public const NOT_IMPLEMENTED = 501;

    /**
     * @param string|null $baseUrl
     * @return void
     */
    public function __construct($baseUrl = null)
    {
        parent::__construct($baseUrl);
    }

    /**
     * @param array $method
     * @param string $route
     * @param string|callable $handler
     * @return void
     */
    private function parseMethod($method, $route, $handler): void
    {
        if (!empty($this->requestChain['route']) || $this->compiler == 'end') {

            $this->compile();
        }

        if (!empty($this->compiler)) {

            $this->counter++;
            $this->groupChain[$this->counter] = [
                "methods" => [
                    $method
                ],
                "route" => $route,
                "handler" => $handler
            ];
        } else {

            $this->requestChain['methods'] = $method;
            $this->requestChain['route'] = $route;
            $this->requestChain['handler'] = $handler;
        }
    }

    /**
     * Include all HTTP verbs to the given route.
     * 
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function any($route, $handler): Router
    {
        $this->parseMethod(self::METHODS, $route, $handler);

        return $this;
    }

    /**
     * Include any valid HTTP verbs provided for the given route.
     * 
     * @param array $methods
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function match($methods, $route, $handler): Router
    {
        $filteredMethods = array_map('strtoupper', is_string($methods) ? (array) $methods : $methods);

        array_map(function ($self) {

            if (!in_array($self, self::METHODS)) {

                $debug = debug_backtrace();
                $this->handleError($self, $debug[0]['line'], $debug[0]['file']);
            }
        }, $filteredMethods);

        $this->parseMethod($filteredMethods, $route, $handler);

        return $this;
    }

    /**
     * Include HTTP GET verb to the given route.
     * 
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function get($route, $handler): Router
    {
        $this->parseMethod(["GET"], $route, $handler);

        return $this;
    }

    /**
     * Include HTTP HEAD verb to the given route.
     * 
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function head($route, $handler): Router
    {
        $this->parseMethod(["HEAD"], $route, $handler);

        return $this;
    }

    /**
     * Include HTTP POST verb to the given route.
     * 
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function post($route, $handler): Router
    {
        $this->parseMethod(["POST"], $route, $handler);

        return $this;
    }

    /**
     * Include HTTP PUT verb to the given route.
     * 
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function put($route, $handler): Router
    {
        $this->parseMethod(["PUT"], $route, $handler);

        return $this;
    }

    /**
     * Include HTTP PATCH verb to the given route.
     * 
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function patch($route, $handler): Router
    {
        $this->parseMethod(["PATCH"], $route, $handler);

        return $this;
    }

    /**Include HTTP DELETE verb to the given route.
     * 
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function delete($route, $handler): Router
    {
        $this->parseMethod(["DELETE"], $route, $handler);

        return $this;
    }

    /**
     * Include HTTP OPTIONS verb to the given route.
     * 
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function options($route, $handler): Router
    {
        $this->parseMethod(["OPTIONS"], $route, $handler);

        return $this;
    }

    /**
     * Apply the given regex in the corresponding route parameter.
     * 
     * @param array $params
     * @return Router
     */
    public function where($params): Router
    {
        if (!empty($this->compiler)) {

            $this->groupChain[$this->counter]['regex'] = $params;
        } else {

            $this->requestChain['regex'] = $params;
        }

        return $this;
    }

    /**
     * Run this middleware before the assigned route or group.
     * 
     * @param string|callable $before
     * @return Router
     */
    public function before($before): Router
    {
        if (!empty($this->compiler) && $this->compiler != 'end') {

            $this->groupChain[$this->counter]['before'] = $before;
        } else {

            $this->requestChain['before'] = $before;
        }

        return $this;
    }

    /**
     * Run this middleware after the assigned route or group.
     * 
     * @param string|callable $after
     * @return Router
     */
    public function after($after): Router
    {
        if (!empty($this->compiler) && $this->compiler != 'end') {

            $this->groupChain[$this->counter]["after"] = $after;
        } else {

            $this->requestChain['after'] = $after;
        }

        return $this;
    }

    /**
     * Defines the namespace of the controller.
     * 
     * @param string $namespace
     * @return Router
     */
    public function namespace($namespace): Router
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Defines the namespace of the middleware.
     * 
     * @param string $middleware
     * @return Router
     */
    public function middleware($middleware): Router
    {
        $this->middleware = $middleware;

        return $this;
    }

    /**
     * Defines a group of routes that share the same middleware or URI.
     * 
     * @param string|callable|null $group
     * @param callable|null $callback
     * @return Router
     */
    public function group($group = "", $callback = null): Router
    {
        if (!empty($this->requestChain['route']) || $this->compiler == 'end') {

            $this->compile();
        }

        if (empty($group)) {

            unset($this->group);

            return $this;
        }

        if (is_string($group)) {

            $group = (trim($group, '/') != '/' ? trim($group, '/') : '');

            if (empty($callback)) {

                $this->group = $group;
            }

            if (!empty($callback) && is_callable($callback)) {

                $this->compiler = 'start';
                $callback($this);
                $this->compiler = 'end';

                for ($i = 0; $i < count($this->groupChain); $i++) {

                    $this->groupChain[$i]['group'] = $group;
                }
            }

            return $this;
        }

        $this->compiler = 'start';
        $group($this);
        $this->compiler = 'end';

        return $this;
    }

    /**
     * @return void
     */
    private function compile(): void
    {
        if (!empty($this->compiler)) {

            foreach ($this->groupChain as $group) {

                if (!empty($group['route'])) {

                    $this->newRoute(
                        $group['methods'][0],
                        $group['route'],
                        $group['handler'],
                        [
                            "regex" => $group['regex'] ?? $this->requestChain['regex'] ?? null,
                            "before" => $group['before'] ?? $this->requestChain['before'] ?? null,
                            "after" => $group['after'] ?? $this->requestChain['after'] ?? null,
                            "namespace" => $this->namespace ?? null,
                            "middleware" => $this->middleware ?? null,
                            "group" => $group['group'] ?? $this->group ?? null,
                        ]
                    );
                }
            }
        }

        if (!empty($this->requestChain['route'])) {

            $this->newRoute(
                $this->requestChain['methods'],
                $this->requestChain['route'],
                $this->requestChain['handler'],
                [
                    "regex" => $this->requestChain['regex'] ?? null,
                    "before" => $this->requestChain['before'] ?? null,
                    "after" => $this->requestChain['after'] ?? null,
                    "namespace" => $this->namespace ?? null,
                    "middleware" => $this->middleware ?? null,
                    "group" => $this->group ?? null
                ]
            );
        }

        unset($this->requestChain);

        if ($this->compiler == 'end') {

            unset($this->groupChain);
        }
    }

    /**
     * Executes the route that matches the URI.
     * 
     * @return void
     */
    public function run(): void
    {
        $this->compile();
        $this->execute();
    }

    /**
     * @param mixed $name
     * @param mixed $arguments
     * @return void
     */
    public function __call($name, $arguments)
    {
        $debug = debug_backtrace();
        $this->handleError($name, $debug[0]['line'], $debug[0]['file']);
    }

    /**
     * @param mixed $name
     * @return void
     */
    private function handleError($name, $line, $file)
    {
        $message = filter_var(strtoupper($name), FILTER_SANITIZE_STRIPPED) . " doesn't exist or is a private method. Error on line {$line} in {$file}.";

        http_response_code(500);

        trigger_error($message, E_USER_ERROR);
    }
}
