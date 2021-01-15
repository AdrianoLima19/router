<?php

require_once "../vendor/autoload.php";

use SurerLoki\Router\Router;

$router = new Router();

$router->namespace("SurerLoki\Router\Demo")
    ->middleware("SurerLoki\Router\Demo")
    ->group();

$router->get('/', 'Web:home');

$router->get('/routes', 'Web:route');

$router->any('/form', 'Web:form');

$router->any('/spoofing', 'Web:spoofing');

$router->get('/class-info', 'Web:info');

$router->get('/get', function () {
    // route used to cause a 405
});

$router->get('/blog', 'Web:blog');

$router->get('/middleware', function ($req, $res) {

    $res->params([
        'request' => $req,
    ]);

    $res->render(
        '/demo/pages/header.php',
        '/demo/pages/nav.php',
    )->send(
        '<div class="container text-center">',
        '<h1>Callback Route</h1>',
        '<h2><span class="badge badge-pill badge-info">Middleware before/after used</span></h2>',
        '</div>'
    )->render(
        '/demo/pages/footer.php'
    );
})->after('Middleware:after')->before(function () {
    echo '<h2><span class="badge badge-warning">Server: middleware executed before page rendering...</span></h2>';
});

$router->fallback('Web:error');

$router->run();
