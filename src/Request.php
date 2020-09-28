<?php

namespace SurerLoki\Router;

class Request
{
    protected $httpMethod;
    protected $url;
    protected $requestRoute;
    private $requestBody;

    /**
     * @param string $url
     */
    public function __construct($url)
    {
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
        $this->rootURL = rtrim($url, '/');
        $this->requestRoute = isset($_GET['route']) ? rtrim(filter_input(INPUT_GET, "route", FILTER_DEFAULT), '/') : $this->getURI($this->rootURL, $_SERVER['REQUEST_URI']);
        $this->requestBody = filter_input_array(INPUT_POST, FILTER_DEFAULT) ?? filter_var_array((array) json_decode(file_get_contents('php://input', false, null, 0, $_SERVER['CONTENT_LENGTH'])), FILTER_DEFAULT) ?? null;
    }

    /**
     * @param string $url
     * @param string|null $get
     * @return string|null
     */
    private function getURI($url, $get)
    {
        $base = explode('/', $url);
        $get = ltrim($get, '/');

        foreach ($base as $key) {
            $uri = str_replace($key, "", $get);
        }
        return ltrim(rtrim($uri, '/'), '/');
    }

    /**
     * @param string $method
     * @param array $data
     * @return array|null
     */
    protected function parseData($method, $data)
    {
        /**
         * https://en.wikipedia.org/wiki/Hypertext_Transfer_Protocol#Request_message
         */

        /**
         * TODO ADD $this->httpMethod verify
         */

        if ($method == 'GET') {
            if (!empty($this->requestBody)) {
                return array_merge($data, $this->requestBody);
            } else {
                return $data;
            }
        }

        if ($method == 'POST') {
            if (!empty($this->requestBody['_method'])) {
                $this->httpMethod = $this->requestBody['_method'];
                $withoutMethod = $this->requestBody;
                unset($withoutMethod['_method']);
                // ? return error 400
                return (!empty($withoutMethod)) ? array_merge($data, $withoutMethod) : $data;
            }

            if (!empty($this->requestBody)) {
                return array_merge($data, $this->requestBody);
            }
            // ? return error 400
            return $data;
        }

        return (!empty($this->requestBody)) ? array_merge($data, $this->requestBody) : $data;
    }
}
