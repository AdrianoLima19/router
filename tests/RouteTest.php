<?php

use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    /**
      * @test route connection 
      * @covers
      */
    public function testInitClass()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $this->assertInstanceOf('\SurerLoki\Router\Router', new \SurerLoki\Router\Router());
    }
    /** @test route method any @covers */
    public function testAnyRouteMethodGET()
    {
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->any('/user', function ($data) {
            echo 'user';
        });
        $this->expectOutputString('user');
        $router->run();
    }
    /**
      * @covers
    */
    public function testAnyRouteMethodPOST()
    {
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $router = new \SurerLoki\Router\Router();
        $router->any('/user', function ($data) {
            echo 'user';
        });
        $this->expectOutputString('user');
        $router->run();
    }
    /**
      * @covers
    */
    public function testAnyRouteMethodPUT()
    {
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $router = new \SurerLoki\Router\Router();
        $router->any('/user', function ($data) {
            echo 'user';
        });
        $this->expectOutputString('user');
        $router->run();
    }
    /**
      * @covers
    */
    public function testAnyRouteMethodPATCH()
    {
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'PATCH';
        $router = new \SurerLoki\Router\Router();
        $router->any('/user', function ($data) {
            echo 'user';
        });
        $this->expectOutputString('user');
        $router->run();
    }
    /**
      * @covers
    */
    public function testAnyRouteMethodDELETE()
    {
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $router = new \SurerLoki\Router\Router();
        $router->any('/user', function ($data) {
            echo 'user';
        });
        $this->expectOutputString('user');
        $router->run();
    }
    /** @test route method match @covers */
    public function testMatchRouteMethodGET()
    {
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->match(['GET'], '/user', function ($data) {
            echo 'user';
        });
        $this->expectOutputString('user');
        $router->run();
    }
    /**
      * @covers
    */
    public function testMatchRouteMethodPOST()
    {
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $router = new \SurerLoki\Router\Router();
        $router->match(['GET', 'POST'], '/user', function ($data) {
            echo 'user';
        });
        $this->expectOutputString('user');
        $router->run();
    }
    /**
      * @covers
    */
    public function testMatchRouteMethodPUT()
    {
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $router = new \SurerLoki\Router\Router();
        $router->match(['GET', 'POST', 'PUT'], '/user', function ($data) {
            echo 'user';
        });
        $this->expectOutputString('user');
        $router->run();
    }
    /** @test route method get @covers */
    public function testGetRoute()
    {
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->get('/', function ($data) {
            echo 'home';
        });
        $this->expectOutputString('home');
        $router->run();
    }
    /** @test route method post @covers */
    public function testPostRoute()
    {
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $router = new \SurerLoki\Router\Router();
        $router->post('/', function ($data) {
            echo 'home';
        });
        $this->expectOutputString('home');
        $router->run();
    }
    /** @test route method put @covers */
    public function testPutRoute()
    {
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $router = new \SurerLoki\Router\Router();
        $router->put('/', function ($data) {
            echo 'home';
        });
        $this->expectOutputString('home');
        $router->run();
    }
    /** @test route method patch @covers */
    public function testPatchRoute()
    {
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'PATCH';
        $router = new \SurerLoki\Router\Router();
        $router->patch('/', function ($data) {
            echo 'home';
        });
        $this->expectOutputString('home');
        $router->run();
    }
    /** @test route method delete @covers */
    public function testDeleteRoute()
    {
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $router = new \SurerLoki\Router\Router();
        $router->delete('/', function ($data) {
            echo 'home';
        });
        $this->expectOutputString('home');
        $router->run();
    }
    /** @test route method options @covers */
    public function testOptionsRoute()
    {
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';
        $router = new \SurerLoki\Router\Router();
        $router->options('/', function ($data) {
            echo 'home';
        });
        $this->expectOutputString('home');
        $router->run();
    }
    /** @test route where @covers */
    public function testWhereIntegerRegex()
    {
        $_SERVER['REQUEST_URI'] = '/user/1a2b3';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->get('/user/{id}', function ($data) {
            echo "user id = {$data['id']}";
        })->where(['id' => "[0-9]+"]);
        $this->expectOutputString('user id = 123');
        $router->run();
    }
    /** @covers */
    public function testWhereStringRegex()
    {
        $_SERVER['REQUEST_URI'] = '/user/A1dr34ia6n7o';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->get('/user/{name}', function ($data) {
            echo "user name = {$data['name']}";
        })->where(['name' => "[a-zA-z]+"]);
        $this->expectOutputString('user name = Adriano');
        $router->run();
    }
    /** @test route before @covers */
    public function testMiddlewareBefore()
    {
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->get('/user', function ($data) {
            echo 'user page';
        })->before(function () {
            echo 'check if logged ';
        });
        $this->expectOutputString('check if logged user page');
        $router->run();
    }
    /** @test route after @covers */
    public function testMiddlewareAfter()
    {
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->get('/user', function ($data) {
            echo 'user page ';
        })->after(function () {
            echo 'do something';
        });
        $this->expectOutputString('user page do something');
        $router->run();
    }
    /** @covers */
    public function testMiddlewareBeforeAfter()
    {
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->get('/user', function ($data) {
            echo 'user page ';
        })->before(function () {
            echo 'check if logged ';
        })->after(function () {
            echo 'do something';
        });
        $this->expectOutputString('check if logged user page do something');
        $router->run();
    }
    /** @test route group */
    public function testGroupRoute()
    {
        $_SERVER['REQUEST_URI'] = '/admin/dash';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->group('/admin');
        $router->get('/dash', function ($data) {
            echo 'admin dashboard';
        });
        $this->expectOutputString('admin dashboard');
        $router->run();
    }
    /** @test route nestedGroups @covers */
    public function testNestedGroupRoute()
    {
        $_SERVER['REQUEST_URI'] = '/admin/dash';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->group('/admin', function () use ($router) {
            $router->get('/dash', function ($data) {
                echo 'admin dashboard';
            });
        });

        $this->expectOutputString('admin dashboard');
        $router->run();
    }
    /** @covers */
    public function testAfterNestedGroupRoute()
    {
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();

        $router->group('/admin', function () use ($router) {
            $router->get('/dash', function ($data) {
                echo 'admin dashboard';
            });
        });

        $router->get('/user', function ($data) {
            echo 'user dashboard';
        });

        $router->run();
        $this->expectOutputString('user dashboard');
    }
    /** @test route 2nestedGroups @covers */
    public function testDoubleNestedGroupRoute()
    {
        $_SERVER['REQUEST_URI'] = '/admin/dash/info';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->group('/admin', function () use ($router) {
            $router->group('/dash', function () use ($router) {
                $router->get('/info', function ($data) {
                    echo 'admin dashboard';
                });
            });
        });

        $router->run();
        $this->expectOutputString('admin dashboard');
    }
}
