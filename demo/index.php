<?php

include "../vendor/autoload.php";

$router = new \SurerLoki\Router\Router();

$router->namespace("SurerLoki\Router\Demo");

$router->get('/', 'Web:home');

$router->match(['GET', 'POST'], '/user', 'Web:user')
    ->before("SurerLoki\Router\Demo\Middleware:before");

$router->match(['PUT', 'DELETE'], '/user/{id}', 'Web:changeUser');

$router->any('/admin', 'Web:admin');

$router->any('/callback', function () {
    echo 'callback';
});

$router->get('/table/{id}', function ($data) {

    echo "user id = {$data['id']}";

    var_dump($data);
})->where(['id' => "[0-9]+"]);

$router->any('/regex/{number}/{string}/{mixed}', function ($data) {

    echo "<h1>Regex</h1>";

    var_dump(
        "Number: {$data['number']}",
        "String: {$data['string']}",
        "Mixed: {$data['mixed']}",
    );
})->where(['number' => "[0-9]+", 'string' => "[a-zA-Z]+"]);

$router->fallback(function ($data) {

    echo "<h1>ERROR {$data['error']}</h1>";

    var_dump(
        $data
    );
});

$router->run();
