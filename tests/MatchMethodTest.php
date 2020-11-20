<?php

use PHPUnit\Framework\TestCase;

final class MatchMethodTest extends TestCase
{
    /** 
     * @test
     * @testdox Test MATCH with any valid method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @uses \SurerLoki\Router\Response
     * @uses \SurerLoki\Router\Dispatch
     */
    public function testAnyValidMatchMethod()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $router = new \SurerLoki\Router\Router();

        $router->match(['GET', 'POST'], '/user', function () {
            echo 'user';
        });

        $this->expectOutputString('user');
        $router->run();
    }

    /** 
     * @test
     * @testdox Test MATCH with GET method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @uses \SurerLoki\Router\Response
     * @uses \SurerLoki\Router\Dispatch
     */
    public function testMatchWithGetMethod()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();

        $router->match(['GET', 'POST'], '/user', function () {
            echo 'user';
        });

        $this->expectOutputString('user');
        $router->run();
    }

    /** 
     * @test
     * @testdox Test MATCH with POST method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @uses \SurerLoki\Router\Response
     * @uses \SurerLoki\Router\Dispatch
     */
    public function testMatchWithPostMethod()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $router = new \SurerLoki\Router\Router();

        $router->match('POST', '/user', function () {
            echo 'user';
        });

        $this->expectOutputString('user');
        $router->run();
    }

    /** 
     * @test
     * @testdox Test MATCH with PUT method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @uses \SurerLoki\Router\Response
     * @uses \SurerLoki\Router\Dispatch
     */
    public function testMatchWithPutMethod()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $router = new \SurerLoki\Router\Router();

        $router->match(['POST', 'PUT'], '/user', function () {
            echo 'user';
        });

        $this->expectOutputString('user');
        $router->run();
    }

    /** 
     * @test
     * @testdox Test MATCH with PATCH method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @uses \SurerLoki\Router\Response
     * @uses \SurerLoki\Router\Dispatch
     */
    public function testMatchWithPatchMethod()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'PATCH';
        $router = new \SurerLoki\Router\Router();

        $router->match(['POST', 'PUT', 'PATCH'], '/user', function () {
            echo 'user';
        });

        $this->expectOutputString('user');
        $router->run();
    }

    /** 
     * @test
     * @testdox Test MATCH with DELETE method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @uses \SurerLoki\Router\Response
     * @uses \SurerLoki\Router\Dispatch
     */
    public function testMatchWithDeleteMethod()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $router = new \SurerLoki\Router\Router();

        $router->match(['POST', 'PUT', 'PATCH', 'DELETE'], '/user', function () {
            echo 'user';
        });

        $this->expectOutputString('user');
        $router->run();
    }

    /** 
     * @test
     * @testdox Test MATCH with OPTIONS method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @uses \SurerLoki\Router\Response
     * @uses \SurerLoki\Router\Dispatch
     */
    public function testMatchWithOptionMethod()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';
        $router = new \SurerLoki\Router\Router();

        $router->match(['POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'], '/user', function () {
            echo 'user';
        });

        $this->expectOutputString('user');
        $router->run();
    }

    /** 
     * @test
     * @testdox Test MATCH with a invalid method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @uses \SurerLoki\Router\Response
     * @uses \SurerLoki\Router\Dispatch
     */
    public function testInvalidMatchMethod()
    {
        $this->expectError();

        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $router = new \SurerLoki\Router\Router();

        $router->match(['INVALID', 'POST'], '/user', function () {
            echo 'user';
        });

        $router->run();
    }
}
