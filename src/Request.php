<?php

namespace SurerLoki\Router;

class Request
{
    protected $data;
    protected $httpMethod;
    protected $url;
    protected $requestRoute;

    public function __construct($url)
    {
        // ! Create a filter for the Input GET
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
        $this->rootURL = rtrim($url, '/');
        $this->requestRoute = isset($_GET['route']) ? rtrim($_GET['route'], '/') : $this->getURI($this->rootURL, $_SERVER['REQUEST_URI']);

        // var_dump([
        //     '$_GET' => $requestRoute,
        //     $_SERVER['REQUEST_URI'],
        //     $_SERVER['REQUEST_METHOD'],
        // ]);
        // die;

        // if ($_SERVER['REQUEST_METHOD'] == "GET") {
        //     return "GET METHOD";
        // }
        // if ($_SERVER['REQUEST_METHOD'] == "POST") {
        //     return "GET METHOD";
        // }
        // if ($_SERVER['REQUEST_METHOD'] == "GET") {
        //     return "GET METHOD";
        // }



        // var_dump([
        //     "GET" => $_GET,
        //     "POST" => $_POST,
        //     // $_SERVER['CONTENT_LENGTH'],
        //     // "PUT,PATCH,DELETE" => $fgc = file_get_contents('php://input'),
        //     "METHOD" => $_SERVER['REQUEST_METHOD'],
        //     "PUT,PATCH,DELETE" => $fgc = file_get_contents('php://input', false, null, 0, $_SERVER['CONTENT_LENGTH']),
        //     parse_str($fgc, $putPatch),
        //     // parse_str(file_get_contents('php://input', false, null, 0, $_SERVER['CONTENT_LENGTH']), $putPatch)
        // ]);
        // die;
    }

    private function getURI($url, $get)
    {
        $base = explode('/', $url);
        $get = ltrim($get, '/');

        foreach ($base as $key) {
            $uri = str_replace($key, "", $get);
        }
        return ltrim(rtrim($uri, '/'), '/');
    }

    protected function parseData($method, $data)
    {
        /**
         * https://en.wikipedia.org/wiki/Hypertext_Transfer_Protocol#Request_message
         */
        var_dump([
            "METHOD" => $method
        ]);
        if ($method == 'GET') {
            if (!empty($_SERVER['CONTENT_LENGTH'])) {
                $return['url'] = $data;
                $return['body'] = (array) json_decode(file_get_contents('php://input', false, null, 0, $_SERVER['CONTENT_LENGTH']));
                return $return;
            } else {
                return $data;
            }
        }

        $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($post['_method']) && in_array($post['_method'], ["HEAD", "PUT", "OPTIONS", "PATCH", "DELETE"])) {

            /** if PUT|PATCH don't have a body return a error */
            $this->httpMethod = $post['_method'];
            $body = $post;
            unset($body['_method']);
        }
        if ($method == 'POST') {
            if ($post) {
                $return['url'] = $data;
                $return['body'] = $post;
                return $return;
            } elseif (!empty($_SERVER['CONTENT_LENGTH'])) {
                $return['url'] = $data;
                $return['body'] = (array) json_decode(file_get_contents('php://input', false, null, 0, $_SERVER['CONTENT_LENGTH']));
                return $return;
            } else {
                return $data;
            }
        }

        var_dump([
            "POST" => $body
        ]);
        die;



        $body = !empty($_SERVER['CONTENT_LENGTH']) ? file_get_contents('php://input', false, null, 0, $_SERVER['CONTENT_LENGTH']) : null;

        var_dump([$body]);
    }
}
