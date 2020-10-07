<?php

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertNotTrue;

class RouteTest extends TestCase
{
    private $root = "http://www.localhost/router/";

    /** @test */
    public function initClass()
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $this->assertInstanceOf('\SurerLoki\Router\Router', new \SurerLoki\Router\Router($this->root));
    }

    /** @test */
    public function uri()
    {
        // Create Router
        $router = new \SurerLoki\Router\Router($this->root);
        $router->route('GET', '/about', function () {
            echo 'about';
        });

        // Fake some data
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $_SERVER['REQUEST_URI'] = '/about/something';

        $method = new ReflectionMethod(
            '\SurerLoki\Router\Router',
            'getUri',
        );

        $method->setAccessible(true);

        $this->assertEquals(
            'about/something',
            $method->invoke(new \SurerLoki\Router\Router($this->root))
        );
    }

    /** @test */
    public function staticRouteOutput()
    {
        $_SERVER['REQUEST_URI'] = '/user';

        $router = new \SurerLoki\Router\Router($this->root);
        $router->route('GET', '/user', function ($data) {
            echo 'user';
        });

        $this->expectOutputString('user');
        $router->run();
    }

    /** @test */
    public function dynamicRouteOutput()
    {
        $_SERVER['REQUEST_URI'] = '/user/Adriano';

        $router = new \SurerLoki\Router\Router($this->root);
        $router->route('GET', '/user/{name}', function ($data) {
            echo 'Wellcome ' . $data['name'];
        });

        $this->expectOutputString('Wellcome Adriano');
        $router->run();
    }

    /** @test */
    public function multipleDynamicRouteOutput()
    {
        $_SERVER['REQUEST_URI'] = '/movies/654/photos/789';

        $router = new \SurerLoki\Router\Router($this->root);
        $router->route('GET', '/movies/{movieId}/photos/{photoId}', function ($data) {
            echo "Cover {$data['photoId']} of the movie {$data['movieId']}";
        });

        $this->expectOutputString("Cover 789 of the movie 654");
        $router->run();
    }
}
