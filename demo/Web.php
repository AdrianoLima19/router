<?php

namespace SurerLoki\Router\Demo;

class Web
{
    public function home($data)
    {
        echo "<h1>Web home</h1>";

        var_dump(
            $data
        );
    }
    public function user($data)
    {
        echo "<h1>Web user</h1>";

        echo "<h2>Method: {$_SERVER['REQUEST_METHOD']}</h2>";

        echo "<h3 style='font-size: 2rem;'>Form Data</h3><br>";
        foreach ($data as $key => $value) {
            echo "<h3>{$key} => {$value}</h3><br>";
        }
    }

    public function changeUser($data)
    {
        echo "<h1>Web change user</h1>";

        var_dump(
            $data
        );
    }

    public function admin($data)
    {
        echo "<h1>Web Admin</h1>";

        var_dump(
            $data
        );
    }
}
