<?php

use PHPUnit\Framework\TestCase;

final class SingleMethodTest extends TestCase
{
    /**
     * @test
     * @testdox Test return of method GET.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testMethodGet()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->get('/home', function () {
            echo 'home';
        });
        $this->expectOutputString('home');
        $router->run();
    }

    /**
     * @test
     * @testdox Test return of method POST.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testMethodPost()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $router = new \SurerLoki\Router\Router();
        $router->post('/user', function () {
            echo 'home';
        });
        $this->expectOutputString('home');
        $router->run();
    }

    /**
     * @test
     * @testdox Test return of method PUT.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testMethodPut()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $router = new \SurerLoki\Router\Router();
        $router->put('/user', function () {
            echo 'update';
        });
        $this->expectOutputString('update');
        $router->run();
    }

    /**
     * @test
     * @testdox Test return of method PATCH.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testMethodPatch()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'PATCH';
        $router = new \SurerLoki\Router\Router();
        $router->patch('/user', function () {
            echo 'change';
        });
        $this->expectOutputString('change');
        $router->run();
    }

    /**
     * @test
     * @testdox Test return of method DELETE.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testMethodDelete()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $router = new \SurerLoki\Router\Router();
        $router->delete('/user', function () {
            echo 'delete';
        });
        $this->expectOutputString('delete');
        $router->run();
    }

    /**
     * @test
     * @testdox Test return of method OPTIONS.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testMethodOptions()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';

        $router = new \SurerLoki\Router\Router();
        $router->options('/', function () {
            echo 'home';
        });
        $this->expectOutputString('home');
        $router->run();
    }
}
