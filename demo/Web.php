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

        var_dump(
            $data
        );
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
