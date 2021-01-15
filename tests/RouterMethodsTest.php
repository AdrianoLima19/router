<?php

use PHPUnit\Framework\TestCase;

final class RouterMethodsTest extends TestCase
{
    /** 
     * @test 
     * @testdox Create a new Instance of Router.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
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
     * @testdox Create a new Instance of Router with a base URL.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testInstanciateRouterWithURL()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router('http://localhost');
        $router->get('/home', function ($request) {

            $this->assertEquals('http://localhost', $request->baseUrl);
        });
        $router->run();
    }

    /** 
     * @test 
     * @testdox Create a new Instance of Router removing the $_REQUEST['uri'].
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testInstanciateRouterWithoutRequestUri()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        unset($_REQUEST['uri']);

        $router = new \SurerLoki\Router\Router();
        $router->get('/home', function ($request) {

            $this->assertEquals('/home', $request->uri);
        });
        $router->run();
    }

    /** 
     * @test 
     * @testdox Test list of registered routes.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testListOfRoutes()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->get('/', function () {
            echo 'out';
        });
        $router->get('/home', function ($req, $res, $server) {
            foreach ($server->list()['GET'] as $route => $value) {
                echo $value['route'] ?? '';
            }
        });
        $router->get('/page', function () {
            echo 'out';
        });
        $router->get('/blog', function () {
            echo 'out';
        });
        $router->get('/info', function () {
            echo 'out';
        });

        $router->run();

        $this->expectOutputString('//home/page/blog/info');
    }

    /** 
     * @test 
     * @testdox Test list of registered routes with fallback.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testListOfRoutesWithFallback()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->get('/', function () {
            echo 'out';
        });
        $router->get('/home', function ($req, $res, $server) {
            foreach ($server->list()['GET'] as $route => $value) {
                echo $value['route'] ?? '';
            }
        });
        $router->get('/page', function () {
            echo 'out';
        });
        $router->get('/blog', function () {
            echo 'out';
        });
        $router->get('/info', function () {
            echo 'out';
        });
        $router->fallback(function () {
            echo 'out';
        });

        $router->run();

        $this->expectOutputString('//home/page/blog/infofallback');
    }

    /** 
     * @test 
     * @testdox Test fallback with controller
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testFallbackController()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/unknow';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->namespace('SurerLoki\Router\Demo');

        $router->get('/', function () {
            echo 'out';
        });

        $router->fallback('Web:testFallback');

        $router->run();

        $this->expectOutputString('fallback test');
    }

    /** 
     * @test 
     * @testdox Test invalid fallback controller
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testInvalidFallbackController()
    {
        $this->expectError();

        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/unknow';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->namespace('SurerLoki\Router\Demo');

        $router->get('/', function () {
            echo 'out';
        });

        $router->fallback('invalid:testFallback');

        $router->run();
    }

    /** 
     * @test 
     * @testdox Test a 404 without a fallback route.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testNotFoundWithoutFallback()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->get('/', function () {
            echo 'out';
        });

        $router->run();

        $this->expectOutputString('');
    }

    /** 
     * @test 
     * @testdox Test a 404 without any route.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testNotFoundWithoutAnyRouteRegistered()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->run();

        $this->expectOutputString('');
    }

    /** 
     * @test 
     * @testdox Test a 404 with only a fallback.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testNotFoundWithOnlyFallback()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->fallback(function ($req) {
            echo $req->error;
        });

        $router->run();

        $this->expectOutputString(404);
    }

    /** 
     * @test 
     * @testdox Test 405 with fallback.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testMethodNotAllowed()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $router = new \SurerLoki\Router\Router();

        $router->get('/home', function () {
            echo 'load page';
        });

        $router->fallback(function ($req) {
            echo $req->error;
        });
        $router->run();

        $this->expectOutputString(405);
    }

    /** 
     * @test 
     * @testdox Test 405 without fallback.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testMethodNotAllowedWithoutFallback()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $router = new \SurerLoki\Router\Router();

        $router->get('/home', function () {
            echo 'load page';
        });

        $router->run();

        $this->expectOutputString('');
    }

    /** 
     * @test 
     * @testdox Test namespaces.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testNamespaces()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->namespace("SurerLoki\Router\Demo")
            ->middleware("SurerLoki\Router\Demo")
            ->group();

        $router->get('/home', 'Web:testController');

        $router->run();

        $this->expectOutputString('web test');
    }

    /** 
     * @test 
     * @testdox Test before middleware callback.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testbeforeMiddlewareCallback()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->namespace("SurerLoki\Router\Demo")
            ->middleware("SurerLoki\Router\Demo")
            ->group();

        $router->get('/home', 'Web:testController')->before(function () {
            echo 'callback before';
        });

        $router->run();

        $this->expectOutputString('callback beforeweb test');
    }

    /** 
     * @test 
     * @testdox Test before middleware controller.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testbeforeMiddlewareController()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->namespace("SurerLoki\Router\Demo")
            ->middleware("SurerLoki\Router\Demo")
            ->group();

        $router->get('/home', 'Web:testController')->before('Middleware:testBefore');

        $router->run();

        $this->expectOutputString('web beforeweb test');
    }

    /** 
     * @test 
     * @testdox Test after middleware callback.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testAfterMiddlewareCallback()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->namespace("SurerLoki\Router\Demo")
            ->middleware("SurerLoki\Router\Demo")
            ->group();

        $router->get('/home', 'Web:testController')->after(function () {
            echo 'callback after';
        });

        $router->run();

        $this->expectOutputString('web testcallback after');
    }

    /** 
     * @test 
     * @testdox Test after middleware controller.
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testAfterMiddlewareController()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->namespace("SurerLoki\Router\Demo")
            ->middleware("SurerLoki\Router\Demo")
            ->group();

        $router->get('/home', 'Web:testController')->after('Middleware:testAfter');

        $router->run();

        $this->expectOutputString('web testweb after');
    }

    /** 
     * @test 
     * @testdox Test query parameters
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testQueryRouteParameters()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user/54';
        $_GET['order'] = 'asc';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->get('/user/{id}', function ($req) {
            echo $req->query->order;
        });

        $router->run();

        $this->expectOutputString('asc');
    }

    /** 
     * @test 
     * @testdox Test dynamic parameters
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testDynamicRouteParameters()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user/54';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->get('/user/{id}', function ($req) {
            echo $req->params->id;
        });

        $router->run();

        $this->expectOutputString(54);
    }

    /** 
     * @test 
     * @testdox Test dynamic param with int
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testDymanicParamsWithWhereConstrain()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user/a3b9c7f';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->get('/user/{id}', function ($req) {
            echo $req->params->id;
        })->where(['id' => '[0-9]+']);

        $router->run();

        $this->expectOutputString(397);
    }

    /** 
     * @test 
     * @testdox Test dynamic param with string
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testDymanicStringParamsWithWhereConstrain()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user/o4pt4i9on1s';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->get('/user/{text}', function ($req) {
            echo $req->params->text;
        })->where(['text' => '[a-zA-Z]+']);

        $router->run();

        $this->expectOutputString('options');
    }

    /** 
     * @test 
     * @testdox Test dynamic param with two params
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testDymanicParamsConstrained()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user/a3b9c7f/o4pt4i9on1s';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->get('/user/{id}/{text}', function ($req) {
            echo $req->params->id;
            echo $req->params->text;
        })->where(['id' => '[0-9]+', 'text' => '[a-zA-Z]+']);

        $router->run();

        $this->expectOutputString('397options');
    }

    /** 
     * @test 
     * @testdox Test redirect method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */

    /** 
     * @test 
     * @testdox Test route method
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testRouteMethod()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->get('/home', function ($req) {
            echo 'home';
        });
        $router->get('/user', function ($req, $res, $server) {
            $server->route('/home');
        });

        $router->run();

        $this->expectOutputString('home');
    }

    /** 
     * @test 
     * @testdox Test route method with a invalid path
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testInvalidRouteMethod()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->get('/home', function ($req) {
            echo 'home';
        });
        $router->get('/user', function ($req, $res, $server) {
            $server->route('/unknow');
        });

        $router->fallback(function () {
            echo 'fallback';
        });
        $router->run();

        $this->expectOutputString('fallback');
    }

    /**
     * @test 
     * @testdox Test group
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testGroup()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home/page';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->group('/home');

        $router->get('/page', function ($req) {
            echo 'loading';
        });

        $router->run();

        $this->expectOutputString('loading');
    }

    /**
     * @test 
     * @testdox Test closed group
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testNestedGroup()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home/page';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->group('/home', function () use ($router) {
            $router->get('/page', function ($req) {
                echo 'loading';
            });
        });

        $router->run();

        $this->expectOutputString('loading');
    }

    /**
     * @test 
     * @testdox Test group after closed group
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testAfterNestedGroup()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/blog';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->group('/home', function () use ($router) {
            $router->get('/page', function ($req) {
                echo 'loading';
            });
        });

        $router->get('/blog', function ($req) {
            echo 'blog';
        });

        $router->run();

        $this->expectOutputString('blog');
    }

    /**
     * @test 
     * @testdox Test group with middleware
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testMiddlewareGroup()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/home/page';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->group('/home', function () use ($router) {
            $router->get('/page', function ($req) {
                echo 'loading';
            });
        })->before(function () {
            echo 'before page ';
        });

        $router->run();

        $this->expectOutputString('before page loading');
    }

    /**
     * @test 
     * @testdox Test group with where 
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testRegexGroup()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/user/table/7a2b3c';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->group('/user', function () use ($router) {
            $router->get('/table/{id}', function ($req) {
                echo 'load table info ' . $req->params->id;
            });
        })->where(['id' => '[0-9]+']);

        $router->run();

        $this->expectOutputString('load table info 723');
    }

    /** 
     * @test 
     * @testdox Test route with a unknow command
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */
    public function testUnknowRouterCommand()
    {
        $this->expectError();

        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->unknow('/', function ($req) {
            echo 'home';
        });

        $router->run();

        $this->expectOutputString('home');
    }

    /** 
     * @test 
     * @testdox Test Response status
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testResponseStatus()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->get('/', function ($req, $res) {
            $res->status(200)->send('Hello World!');
        });

        $router->run();

        $this->expectOutputString('Hello World!');
    }

    /** 
     * @test 
     * @testdox Test Response Params
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testResponseParams()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->get('/', function ($req, $res) {
            $res->params(['req' => $req]);
            $res->render(
                dirname(__DIR__) . "\demo\pages\\test.php"
            );
        });

        $router->run();

        $this->expectOutputString('/');
    }

    /** 
     * @test 
     * @testdox Test Request Body Form
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */

    /**
     * @test 
     * @testdox Test Request Body Json
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */

    /**
     * @test 
     * @testdox Test Request Body Xml
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     */

    /** 
     * @test 
     * @testdox Test Response Render
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testRenderResponse()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->get('/', function ($req, $res) {
            $res->render(
                dirname(__DIR__) . "/demo/pages/test.php"
            );
        });

        $router->run();

        $this->expectOutputString('test render');
    }

    /** 
     * @test 
     * @testdox Test Response Json
     * @covers \SurerLoki\Router\Router
     * @covers \SurerLoki\Router\Core
     * @covers \SurerLoki\Router\Request
     * @covers \SurerLoki\Router\Response
     * @covers \SurerLoki\Router\Dispatch
     * @runInSeparateProcess
     */
    public function testJsonResponse()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $router = new \SurerLoki\Router\Router();

        $router->get('/', function ($req, $res) {
            $res->json([
                'status' => 200,
                'message' => 'random message',
            ]);
        });

        $router->run();

        $this->expectOutputString('{"status":200,"message":"random message"}');
    }
}
