<?php

namespace SurerLoki\Router\Demo;

class Middleware
{
    public function after()
    {
        echo '<h2><span class="badge badge-secondary">Controller: Middleware executed after page rendering...</span></h2>';
    }

    public function testbefore()
    {
        echo 'web before';
    }

    public function testAfter()
    {
        echo 'web after';
    }
}
