<?php

include "../vendor/autoload.php";

$router = new \SurerLoki\Router\Router();

$router->namespace("SurerLoki\Router\Demo");

$router->get('/', 'Web:home');
$router->match(['GET', 'POST'], '/user', 'Web:user')->before("SurerLoki\Router\Demo\Middleware:before");
$router->match(['PUT', 'DELETE'], '/user/{id}', 'Web:changeUser');

$router->any('/admin', function () {
    echo 'admin';
});

$router->get('/table/{id}', function ($data) {
    echo "user id = {$data['id']}";
})->where(['id' => "[0-9]+"]);

$router->fallback(function ($data) {

    echo "<h1>ERROR {$data['error']}</h1>";

    var_dump(
        $data
    );
});

$router->run();
