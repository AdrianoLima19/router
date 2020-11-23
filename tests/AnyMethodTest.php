<?php

use PHPUnit\Framework\TestCase;

final class AnyMethodTest extends TestCase
{
    /** 
     * @test
     * @testdox Test ANY with GET method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testAnyRouteMethodGet()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->any('/user', function () {
            echo 'user';
        });
        $this->expectOutputString('user');
        $router->run();
    }

    /** 
     * @test
     * @testdox Test ANY with POST method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testAnyRouteMethodPost()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $router = new \SurerLoki\Router\Router();
        $router->any('/user', function () {
            echo 'user';
        });
        $this->expectOutputString('user');
        $router->run();
    }

    /** 
     * @test
     * @testdox Test ANY with PUT method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testAnyRouteMethodPut()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $router = new \SurerLoki\Router\Router();
        $router->any('/user', function () {
            echo 'user';
        });
        $this->expectOutputString('user');
        $router->run();
    }

    /** 
     * @test
     * @testdox Test ANY with PATCH method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testAnyRouteMethodPatch()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'PATCH';
        $router = new \SurerLoki\Router\Router();
        $router->any('/user', function () {
            echo 'user';
        });
        $this->expectOutputString('user');
        $router->run();
    }

    /** 
     * @test
     * @testdox Test ANY with DELETE method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testAnyRouteMethodDelete()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $router = new \SurerLoki\Router\Router();
        $router->any('/user', function () {
            echo 'user';
        });
        $this->expectOutputString('user');
        $router->run();
    }

    /** 
     * @test
     * @testdox Test ANY with OPTIONS method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testAnyRouteMethodOption()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';
        $router = new \SurerLoki\Router\Router();
        $router->any('/user', function () {
            echo 'user';
        });
        $this->expectOutputString('user');
        $router->run();
    }
}
