<?php

namespace SurerLoki\Router;

use stdClass;

/**
 * @author Adriano Lima de Souza <surerloki3379@gmail.com>
 * @license MIT
 * @package library
 */
class Response
{
    /** @var int */
    private $status;

    /** @var object */
    private $params;

    public function __construct()
    {
        $this->params = new stdClass;
    }

    /**
     * @param string ...$item
     * @return void
     */
    public function send(...$item)
    {
        if (!empty($this->status)) {

            http_response_code($this->status);
        }

        foreach ($item as $key) {

            if (strip_tags($key) == $key) {

                header('Content-type: text/plain');
            } else {

                header('Content-type: text/html');
            }

            echo $key;
        }

        return $this;
    }

    /**
     * @param array ...$json
     * @return Response
     */
    public function json(...$json)
    {
        if (!empty($this->status)) {

            http_response_code($this->status);
        }

        foreach ($json as $key) {

            foreach ($key as $needle => $value) {

                $encode[$needle] = $value;
            }
        }

        header('Content-type: application/json');
        echo json_encode($encode);

        return $this;
    }

    /**
     * @param int $status
     * @return void
     */
    public function status($status)
    {
        $this->status = filter_var($status, FILTER_VALIDATE_INT);

        return $this;
    }

    /**
     * @param array ...$files
     * @return Response
     */
    public function render(...$files)
    {
        $params = $this->params ?? null;

        if (!empty($this->status)) {

            http_response_code($this->status);
        }

        array_map(function ($self) use ($params) {

            $filter = trim(filter_var($self, FILTER_SANITIZE_STRIPPED), "/\\");
            $render = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, dirname(__DIR__) . '/' . $filter);

            if (file_exists($render)) {

                include $render;
            }
        }, $files);

        return $this;
    }

    /**
     * @param array ...$variable
     * @return Response
     */
    public function params(...$variable)
    {
        foreach ($variable as $position) {

            foreach ($position as $key => $value) {

                $this->params->$key = $value;
            }
        }

        return $this;
    }
}
