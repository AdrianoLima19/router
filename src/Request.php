<?php

namespace SurerLoki\Router;

class Request
{
    private string $httpMethod;
    private ?string $rootUrl;
    private string $uri;
    private ?array $request;

    /**
     * @param string|null $url
     */
    public function __construct($url = null)
    {
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
        $this->rootUrl = (!empty($url)) ? filter_var($url, FILTER_SANITIZE_URL) : null;

        $this->parseURL($this->rootUrl);

        $this->request = $this->parsePost() ?? $this->parseStream();
    }

    /**
     * @param string|null $url
     * @return string|null
     */
    private function parseURL($url)
    {
        if (!empty($_GET['uri'])) {
            $this->uri = filter_input(INPUT_GET, "uri", FILTER_SANITIZE_SPECIAL_CHARS);
            return;
        }

        if (empty($url)) {
            $this->uri = substr(rawurldecode($_SERVER['REQUEST_URI']), strlen(implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/'));
            return;
        }
    }

    /**
     * @return array|null
     */
    private function parsePost()
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ?? null;

        if (empty($post)) {
            return $post;
        }

        if (!empty($post['_method']) && in_array(strtoupper($post['_method']), Router::METHODS)) {
            $this->httpMethod = strtoupper($post['_method']);
            $_SERVER['REQUEST_METHOD'] = strtoupper($post['_method']);
            unset($post['_method']);
            return (!empty($post)) ? $post : null;
        }

        return $post;
    }

    /**
     * @return array|null
     */
    private function parseStream()
    {
        if (empty($_SERVER['CONTENT_LENGTH'])) {
            return null;
        }

        if ($_SERVER['CONTENT_TYPE'] == 'application/json') {
            $toArray = json_decode(file_get_contents('php://input', false, null, 0, $_SERVER['CONTENT_LENGTH']), true);
            return filter_var_array($toArray, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        if ($_SERVER['CONTENT_TYPE'] == 'application/xml') {
            $xml = simplexml_load_string(file_get_contents('php://input', false, null, 0, $_SERVER['CONTENT_LENGTH']));
            $toArray = json_decode(json_encode($xml), true);
            return filter_var_array($toArray, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return array|null
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /**
     * @return string|null
     */
    public function getRootUrl()
    {
        return $this->rootUrl;
    }
}
