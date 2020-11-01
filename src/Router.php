<?php

namespace SurerLoki\Router;

/**
 * @author Adriano Lima de Souza <surerloki3379@gmail.com>
 * @package library
 * @version 1.0.1
 */
final class Router extends Core
{
    private $requestChain;
    private $namespace;
    private $keepNamespace;
    private $group;
    private $nested;
    private $invert;

    /** @var METHODS HTTP Methods */
    public const METHODS = ['GET', 'HEAD', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    public const BAD_REQUEST = 400;
    public const NOT_FOUND = 404;
    public const METHOD_NOT_ALLOWED = 405;
    public const NOT_IMPLEMENTED = 501;
    public const INTERNAL_ERROR = 500;

    /**
     * @param string $route
     * @param string|callable $handler
     */
    public function any($route, $handler)
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
     */
    public function match($methods, $route, $handler)
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
     */
    public function get($route, $handler)
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
     */
    public function post($route, $handler)
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
     */
    public function put($route, $handler)
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
     */
    public function patch($route, $handler)
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
     */
    public function delete($route, $handler)
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
     */
    public function options($route, $handler)
    {
        $this->compile();

        $this->requestChain['methods'] = ['OPTIONS'];
        $this->requestChain['route'] = $route;
        $this->requestChain['handler'] = $handler;

        return $this;
    }

    /**
     * @param array $params
     */
    public function where($params)
    {
        $this->requestChain['regex'] = $params;

        return $this;
    }

    /**
     * @param string|callable $before
     */
    public function before($before)
    {
        $this->requestChain['before'] = $before;

        return $this;
    }

    /**
     * @param string|callable $after
     */
    public function after($after)
    {
        $this->requestChain['after'] = $after;

        return $this;
    }

    public function middleware($type)
    {
        array_map(function ($self) {
            if (in_array(strtolower($self), ['before', 'after'])) {
                //
            }
        }, is_string($type) ? (array) $type : $type);

        return $this;
    }

    /**
     * @param string $group
     * @param string|callable|null $callback
     */
    public function group($group, $callback = null)
    {
        if (!empty($this->requestChain['route'])) {
            $this->compile();
        }

        $group = (trim($group, '/') != '/' ? trim($group, '/') : '');

        if (empty($callback)) {
            $this->group = $group;
            return;
        }

        if (empty($this->nested['group'])) {

            $this->nested['group'] = $group;
            $callback($this);
            $this->invert['group'] = true;
            return;
        } else {

            $this->nested['group'] .= "/{$group}";
            $callback($this);
            return;
        }
        return $this;
    }

    /**
     * @param string $namespace
     * @param string|callable|null $callback
     */
    public function namespace($namespace, $callback = null)
    {
        if (!empty($this->requestChain['route'])) {
            $this->compile();
        }

        if (empty($callback)) {
            $this->namespace = $namespace;
            unset($this->invert['namespace'], $this->nested['namespace'], $this->keepNamespace);
            return;
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

    public function compile()
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
                    "group" => (!empty($this->nested['group'])) ? $this->nested['group'] : $this->group ?? null,
                ]
            );

            if (
                !empty($this->invert['namespace'])
                && !empty($this->keepNamespace)
                && empty($this->nested['namespace'])
            ) {
                unset($this->keepNamespace);
            }

            if (!empty($this->invert['group'])) {
                unset($this->nested['group'], $this->invert['group']);
            }

            if (!empty($this->invert['namespace'])) {
                unset($this->nested['namespace'], $this->invert['namespace']);
            }

            unset($this->requestChain);
            return;
        }
    }

    public function run()
    {
        $this->compile();
        $this->execute();
    }
}
