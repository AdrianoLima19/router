<?php

namespace SurerLoki\Router;

trait Core
{
    protected $data;
    protected $error;
    protected $routes;
    private $namespace;
    private $group;
    private $nested;

    /**
     * @param string $method
     * @param string $route
     * @param string|callable $handler
     */
    protected function newRoute($method, $route, $handler)
    {
        $requestRoute = explode("/", $this->requestRoute);
        $urlData = array_values(array_diff($requestRoute, explode("/", rtrim($route, '/'))));

        // ? if in future updates url accept regex parameters verify here
        preg_match_all('/\{\s*([a-zA-Z0-9_-]*)\}/', $route, $keys, PREG_SET_ORDER);

        for ($key = 0; $key < count($keys); $key++) {
            $data[$keys[$key][1]] = ($urlData[$key]) ?? null;
        }

        $this->data = $this->parseData($method, ($data) ?? []);

        $route = (!empty($this->group)) ? "/{$this->group}{$route}" : $route;
        var_dump([
            "METHOD" => $this->httpMethod,
            "NAMESPACE" => $this->namespace . '\\' . $handler,
            "GROUP" => $route,
            // "ROUTE" => $route,
            // "DATA" => $this->data,
            "ERROR" => $this->error,
        ]);
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

        // TODO add a nested option to group

        $keepGroup = $this->group;
        $this->group = $group;
        $callback($this);
        $this->group = $keepGroup;
    }
}
