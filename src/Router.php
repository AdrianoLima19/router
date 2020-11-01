<?php

namespace SurerLoki\Router;

/**
 * @author Adriano Lima de Souza <surerloki3379@gmail.com>
 * @package library
 */
final class Router extends Core
{
    /** @var array|null */
    private $requestChain;

    /** @var string|null */
    private $namespace;

    /** @var string|null */
    private $keepNamespace;

    /** @var string|null */
    private $group;

    /** @var array|null */
    private $nested;

    /** @var array|null */
    private $invert;

    /** @var array HTTP verbs */
    public const METHODS = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    /** @var int HTTP error code */
    public const BAD_REQUEST = 400;

    /** @var int HTTP error code */
    public const NOT_FOUND = 404;

    /** @var int HTTP error code */
    public const METHOD_NOT_ALLOWED = 405;

    /** @var int HTTP error code */
    public const NOT_IMPLEMENTED = 501;

    /** @var int HTTP error code */
    public const INTERNAL_ERROR = 500;

    /**
     * @param string|null $route
     * @param string|callable $handler
     * @return Router
     */
    public function any($route, $handler): Router
    {
        $this->compile();

        $this->requestChain['methods'] = self::METHODS;
        $this->requestChain['route'] = $route;
        $this->requestChain['handler'] = $handler;

        return $this;
    }

    /**
     * @param string|array $methods
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function match($methods, $route, $handler): Router
    {
        $this->compile();

        $filteredMethods = array_map('strtoupper', is_string($methods) ? (array) $methods : $methods);

        array_map(function ($self) {
            if (!in_array($self, self::METHODS)) {
                http_response_code(self::METHOD_NOT_ALLOWED);
                throw new \Exception("Method {$self} not allowed", 405);
            }
        }, $filteredMethods);

        $this->requestChain['methods'] = $filteredMethods;
        $this->requestChain['route'] = $route;
        $this->requestChain['handler'] = $handler;

        return $this;
    }

    /**
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function get($route, $handler): Router
    {
        $this->compile();

        $this->requestChain['methods'] = ['GET', 'HEAD'];
        $this->requestChain['route'] = $route;
        $this->requestChain['handler'] = $handler;

        return $this;
    }

    /**
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function post($route, $handler): Router
    {
        $this->compile();

        $this->requestChain['methods'] = ['POST'];
        $this->requestChain['route'] = $route;
        $this->requestChain['handler'] = $handler;

        return $this;
    }

    /**
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function put($route, $handler): Router
    {
        $this->compile();

        $this->requestChain['methods'] = ['PUT'];
        $this->requestChain['route'] = $route;
        $this->requestChain['handler'] = $handler;

        return $this;
    }

    /**
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function patch($route, $handler): Router
    {
        $this->compile();

        $this->requestChain['methods'] = ['PATCH'];
        $this->requestChain['route'] = $route;
        $this->requestChain['handler'] = $handler;

        return $this;
    }

    /**
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function delete($route, $handler): Router
    {
        $this->compile();

        $this->requestChain['methods'] = ['DELETE'];
        $this->requestChain['route'] = $route;
        $this->requestChain['handler'] = $handler;

        return $this;
    }

    /**
     * @param string $route
     * @param string|callable $handler
     * @return Router
     */
    public function options($route, $handler): Router
    {
        $this->compile();

        $this->requestChain['methods'] = ['OPTIONS'];
        $this->requestChain['route'] = $route;
        $this->requestChain['handler'] = $handler;

        return $this;
    }

    /**
     * @param array $params
     * @return Router
     */
    public function where($params): Router
    {
        $this->requestChain['regex'] = $params;

        return $this;
    }

    /**
     * @param string|callable $before
     * @return Router
     */
    public function before($before): Router
    {
        $this->requestChain['before'] = $before;

        return $this;
    }

    /**
     * @param string|callable $after
     * @return Router
     */
    public function after($after): Router
    {
        $this->requestChain['after'] = $after;

        return $this;
    }

    public function middleware($type)
    {
        array_map(function ($self) {
            if (in_array(strtolower($self), ['before', 'after'])) {
                // ? define how the middleware will be implemented
            }
        }, is_string($type) ? (array) $type : $type);

        return $this;
    }

    /**
     * @param string $group
     * @param string|callable|null $callback
     * @return Router
     */
    public function group($group, $callback = null): Router
    {
        if (!empty($this->requestChain['route'])) {
            $this->compile();
        }

        $group = (trim($group, '/') != '/' ? trim($group, '/') : '');

        if (empty($callback)) {
            $this->group = $group;
            return $this;
        }
        $this->nested['base'] = $group;

        if (empty($this->nested['group'])) {

            $this->nested['group'] = $group;
            $this->invert['group'] = 'placeholder';
            $callback($this);
            $this->nested['group'] = $group;
            // return;
        } else {
            $callback($this);
            $this->invert['group'] = "{$this->nested['group']}/{$group}";
            return $this;
        }

        $this->compile($remove = true);
        return $this;
    }

    /**
     * @param string $namespace
     * @param string|callable|null $callback
     * @return Router
     */
    public function namespace($namespace, $callback = null): Router
    {
        if (!empty($this->requestChain['route'])) {
            $this->compile();
        }

        if (empty($callback)) {
            $this->namespace = $namespace;
            unset($this->invert['namespace'], $this->nested['namespace'], $this->keepNamespace);
            return $this;
        }

        if (!empty($this->nested['namespace'])) {
            $this->keepNamespace = $this->nested['namespace'];
            $this->nested['namespace'] = $namespace;
        }
        $this->nested['namespace'] = $namespace;
        $callback($this);
        $this->invert['namespace'] = true;

        return $this;
    }

    /**
     * @param boolean $remove
     * @return void
     */
    public function compile($remove = false): void
    {
        if (!empty($this->requestChain)) {

            $this->newRoute(
                $this->requestChain['methods'],
                $this->requestChain['route'],
                $this->requestChain['handler'],
                [
                    "regex" => $this->requestChain['regex'] ?? null,
                    "before" => $this->requestChain['before'] ?? null,
                    "after" => $this->requestChain['after'] ?? null,
                    "namespace" => !empty($this->nested['namespace'])
                        ? $this->nested['namespace']
                        : (!empty($this->keepNamespace)
                            ? $this->keepNamespace
                            : $this->namespace ?? null),
                    "group" => (!empty($this->invert['group']) && $this->invert['group'] != 'placeholder' && $this->invert['group'] != 'nested') ? $this->invert['group']
                        : (!empty($this->nested['group']) ? $this->nested['group'] : ($this->group ?? null))

                ]
            );

            if (
                !empty($this->invert['namespace'])
                && !empty($this->keepNamespace)
                && empty($this->nested['namespace'])
            ) {
                unset($this->keepNamespace);
            }

            if (!empty($this->nested['group']) && $this->invert['group'] == 'nested') {
                unset($this->nested['group']);
            }

            if (!empty($this->invert['group']) && $this->invert['group'] != 'placeholder') {
                $this->invert['group'] = 'nested';
            }

            if (!empty($this->invert['namespace'])) {
                unset($this->nested['namespace'], $this->invert['namespace']);
            }

            unset($this->requestChain);
            if ($remove) {
                unset($this->nested['group'], $this->invert['group']);
            }
            return;
        }
    }

    public function run(): void
    {
        $this->compile();
        $this->execute();
    }
}
