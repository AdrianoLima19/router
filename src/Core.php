<?php

namespace SurerLoki\Router;

trait Core
{
    protected $data;

    protected function newRoute($method, $route, $handler)
    {
        $requestRoute = explode("/", $this->requestRoute);
        $urlData = array_values(array_diff($requestRoute, explode("/", rtrim($route, '/'))));

        preg_match_all('/\{\s*([a-zA-Z0-9_-]*)\}/', $route, $keys, PREG_SET_ORDER);

        for ($key = 0; $key < count($keys); $key++) {
            $data[$keys[$key][1]] = ($urlData[$key]) ?? null;
        }

        $this->data = $this->parseData($method, $data);

        var_dump(
            "DATA",
            $this->data
        );
    }

    public function namespace()
    {
    }

    public function group()
    {
    }
}
