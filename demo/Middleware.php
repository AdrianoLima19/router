<?php

namespace SurerLoki\Router\Demo;

class Middleware
{
    public function before()
    {
        echo "<h1>Middleware Before</h1>";
    }

    public function after()
    {
        echo "<h3>Middleware After</h3>";
    }
}
