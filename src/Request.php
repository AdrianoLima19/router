<?php

namespace SurerLoki\Router;

/**
 * @author Adriano Lima de Souza <surerloki3379@gmail.com>
 * @license MIT
 * @package library
 */
class Request
{
    /** @var string|null */
    private $baseUrl;

    /** @var string|null */
    private $uri;

    /** @var string */
    private $method;

    /** @var string */
    private $format;

    /** @var object|null */
    private $query;

    /** @var object|null */
    private $body;

    /** @var object|null */
    private $params;

    /**
     * @param string|null $baseUrl
     * @return void
     */
    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl ?? null;
        $this->format = $_SERVER['CONTENT_TYPE'] ?? null;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $this->parseUri();
        $this->query = (object) $_GET;
        unset($this->query->uri);
        $this->body = $this->parseBody();
    }

    /**
     * @return string|null
     */
    private function parseUri(): ?string
    {
        if (!empty($_GET['uri'])) {

            return '/' . filter_input(INPUT_GET, "uri", FILTER_SANITIZE_SPECIAL_CHARS);
        }

        $uri = '/' . substr(rawurldecode($_SERVER['REQUEST_URI']), strlen(implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/'));

        if ($replace = strpos($uri, '?')) {

            return substr($uri, 0, $replace);
        }

        return $uri;
    }

    /**
     * @return object
     */
    private function parseBody(): object
    {
        $post = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS) ?? null;

        if (!empty($post['_method']) && in_array(strtoupper($post['_method']), Router::METHODS)) {

            $_SERVER['REQUEST_METHOD'] = $this->method = strtoupper($post['_method']);
            unset($post['_method']);

            return (!empty($post)) ? (object) $post : (object) null;
        }

        if (!empty($post)) {

            return (object) $post;
        }

        if (!empty($_SERVER['CONTENT_LENGTH'])) {

            $stream = file_get_contents('php://input', false, null, 0, $_SERVER['CONTENT_LENGTH']);

            if ($_SERVER['CONTENT_TYPE'] == 'application/json') {

                $decodeJson = json_decode($stream, true);

                return (object) filter_var_array($decodeJson, FILTER_SANITIZE_SPECIAL_CHARS);
            }

            if ($_SERVER['CONTENT_TYPE'] == 'application/xml') {

                $getXml = simplexml_load_string($stream);
                $decodeJson = json_decode(json_encode($getXml), true);

                return (object) filter_var_array($decodeJson, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return (object) [];
    }

    /**
     * @return object
     */
    public function getRequest(): object
    {
        return (object) [
            "baseUrl" => $this->baseUrl,
            "method" => $this->method,
            "format" => $this->format,
            "uri" => $this->uri,
            "query" => $this->query,
            "body" => $this->body,
            "params" => $this->params,
        ];
    }

    /**
     * @param array|object $params
     * @return self
     */
    public function setParams($params): Request
    {
        $this->params = (object) filter_var_array($params, FILTER_SANITIZE_SPECIAL_CHARS);

        return $this;
    }
}
