<?php

namespace SurerLoki\Router\Demo;

class Middleware
{
    public function after()
    {
        echo '<h2><span class="badge badge-secondary">Controller: Middleware after executed...</span></h2>';
    }
}
