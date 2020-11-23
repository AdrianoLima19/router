<?php

use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    /** 
     * @test 
     * @testdox Create a new Instance of Router.
     * @coversNothing
     */
    public function testInstanciateRouter()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/';

        $this->assertInstanceOf('\SurerLoki\Router\Router', new \SurerLoki\Router\Router());
    }

    /** 
     * @test
     * @testdox Test params from $request
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testRequestParams()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->get('/home', function ($request) {

            $this->assertEquals((object) [
                "baseUrl" => null,
                "method" => "GET",
                "format" => null,
                "uri" => '/home',
                "query" => (object) [],
                "body" => (object) [],
                "params" => (object) [],
            ], $request);
        });
        $router->run();
    }

    /** 
     * @test
     * @testdox Test $response send method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testResponseSend()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->get('/home', function ($request, $response) {
            $response->send('Hello World!');
        });

        $this->expectOutputString('Hello World!');
        $router->run();
    }

    /** 
     * @test
     * @testdox Test $response json method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testResponseJson()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->get('/home', function ($request, $response) {
            $response->json(['status' => 'ok', 'message' => 'no problem to load']);
        });

        $this->expectOutputString('{"status":"ok","message":"no problem to load"}');
        $router->run();
    }

    /**
     * @test
     * @testdox Test redirect to fallback on page not found (404)
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     * @runInSeparateProcess
     */
    public function testFallback()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->get('/', function ($request, $response) {
            $response->send('Hello World!');
        });
        $router->fallback(function ($request, $response) {
            $response->send('Error route not found');
        });
        $this->expectOutputString('Error route not found');

        $router->run();
    }

    /**
     * @test
     * @testdox Test 404 with fallback
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     * @runInSeparateProcess
     */
    public function test404withFallback()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/unknow';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->get('/', function ($request, $response) {
            $response->send('Hello World!');
        });
        $router->fallback(function ($request, $response) {
            $response->send('Error route not found');
        });
        $this->expectOutputString('Error route not found');

        $router->run();
    }

    /**
     * @test
     * @testdox Test 405 with fallback
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     * @runInSeparateProcess
     */
    public function test405withFallback()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $router = new \SurerLoki\Router\Router();
        $router->get('/', function ($request, $response) {
            $response->send('Hello World!');
        });
        $router->fallback(function ($request, $response) {
            $response->send('Error method not allowed');
        });
        $this->expectOutputString('Error method not allowed');

        $router->run();
    }

    /**
     * @test
     * @testdox Test fallback without any other route
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     * @runInSeparateProcess
     */
    public function testFallbackWithNoRoutes()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $router = new \SurerLoki\Router\Router();
        $router->fallback(function ($request, $response) {
            $response->send($request->error);
        });
        $this->expectOutputString(404);

        $router->run();
    }

    /** 
     * @test
     * @testdox group with URI
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testGroupOfUris()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/admin/dash';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->group('/admin', function () use ($router) {
            $router->get('/dash', function ($request, $response) {
                $response->send('admin dashboard');
            });
        });
        $this->expectOutputString('admin dashboard');

        $router->run();
    }

    /** 
     * @test
     * @testdox Test second route from group
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testSecondRouteFromGroup()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/admin/user';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->group('/admin', function () use ($router) {
            $router->get('/dash', function ($request, $response) {
                $response->send('admin dashboard');
            });
            $router->get('/user', function ($request, $response) {
                $response->send('admin user');
            });
        });
        $this->expectOutputString('admin user');

        $router->run();
    }

    /** 
     * @test
     * @testdox Test third route from group
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testThirdRouteFromGroup()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/admin/blog';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->group('/admin', function () use ($router) {
            $router->get('/dash', function ($request, $response) {
                $response->send('admin dashboard');
            });
            $router->get('/user', function ($request, $response) {
                $response->send('admin user');
            });
            $router->get('/blog', function ($request, $response) {
                $response->send('blog panel');
            });
        });
        $this->expectOutputString('blog panel');

        $router->run();
    }

    /** 
     * @test
     * @testdox Test group with Middleware
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testGroupOfMiddlewares()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/admin/blog';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->group('/admin', function () use ($router) {
            $router->get('/dash', function ($request, $response) {
                $response->send('admin dashboard');
            });
            $router->get('/user', function ($request, $response) {
                $response->send('admin user');
            });
            $router->get('/blog', function ($request, $response) {
                $response->send('blog panel');
            });
        })->before(function ($request, $response) {
            $response->send('middleware running before: ');
        });
        $this->expectOutputString('middleware running before: blog panel');

        $router->run();
    }

    /** 
     * @test
     * @testdox Test middleware from route inside group of middlewares
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testRouteMiddlewareInsideGroupOfMiddlewares()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/admin/dash';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->group('/admin', function () use ($router) {
            $router->get('/dash', function ($request, $response) {
                $response->send('admin dashboard');
            })->before(function ($request, $response) {
                $response->send('middleware dash before: ');
            });
            $router->get('/user', function ($request, $response) {
                $response->send('admin user');
            });
            $router->get('/blog', function ($request, $response) {
                $response->send('blog panel');
            });
        })->before(function ($request, $response) {
            $response->send('middleware running before: ');
        });
        $this->expectOutputString('middleware dash before: admin dashboard');

        $router->run();
    }

    /** 
     * @test
     * @testdox Test middleware after
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testGroupOfAfterMiddleware()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/admin/user';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->group('/admin', function () use ($router) {
            $router->get('/dash', function ($request, $response) {
                $response->send('admin dashboard');
            });
            $router->get('/user', function ($request, $response) {
                $response->send('admin user');
            });
            $router->get('/blog', function ($request, $response) {
                $response->send('blog panel');
            });
        })->after(function ($request, $response) {
            $response->send(' :middleware running after');
        });
        $this->expectOutputString('admin user :middleware running after');

        $router->run();
    }

    /** 
     * @test
     * @testdox Test route after middleware
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testRouteAfterMiddlewareInsideGroupOfMiddlewares()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/admin/blog';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router = new \SurerLoki\Router\Router();
        $router->group('/admin', function () use ($router) {
            $router->get('/dash', function ($request, $response) {
                $response->send('admin dashboard');
            });
            $router->get('/user', function ($request, $response) {
                $response->send('admin user');
            });
            $router->get('/blog', function ($request, $response) {
                $response->send('blog panel');
            })->after(function ($request, $response) {
                $response->send(' :blog middleware running after');
            });
        })->after(function ($request, $response) {
            $response->send(' :middleware running after');
        });
        $this->expectOutputString('blog panel :blog middleware running after');

        $router->run();
    }
}
