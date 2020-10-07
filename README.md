# SurerLoki\Router

[![license](https://img.shields.io/github/license/AdrianoLima19/router)](https://github.com/AdrianoLima19/router/blob/master/LICENSE)
![php](https://img.shields.io/packagist/php-v/surerloki/router)
![version](https://img.shields.io/packagist/v/surerloki/router)

Router is a object-oriented library to handle HTTP requests.

## Features

- Can be used with all HTTP methods
- [Single requests methods as `get()`, `post()`, `put()`, …](#static-route)
- [Static Route Patterns](#Static-Route)
- [Dynamic routing with named route parameters](#dynamic-route)

## Requirements

<ul style="list-style:circle;padding-left:1.5rem;margin-left:0;">
<li><a href="https://getcomposer.org/doc/01-basic-usage.md#package-versions" target="_blank">Composer</a></li>
<li><a href="https://www.php.net/downloads" target="_blank">PHP</a> 7.3^</li>
</ul>

## Installation

Installation is available via Composer:

```json
"surerloki/router": "^0.2.1"
```

or run

```sh
composer require surerloki/router ^0.2.1
```

### Usage

Call and instantiate router

```php
// Require autoload
require __DIR__ . "../vendor/autoload.php";
// Define root project url
$url = "http://localhost/router/";
// Create router class
$router = new \SurerLoki\Router\Router($url);

/**
 * Routes
 */

// Executes the routes
$router->run()
```

#### Namespace

Call the controller namespace

```php
$router->namespace("App\Controllers");

$router->get("/", "Controller:handler"); // App\Controllers\Controler
```

#### Groups

Group request methods

```php
$router->group("/admin");
$router->get("/", "Controller:handler"); // /admin/
$router->get("/dashboard", "Controller:handler"); // /admin/dashboard
```

Groups can be created as a callback

```php
$router->group("/admin");
$router->get("/options", "Controller:handler"); // /admin/options

$router->group("/movie", function () use ($router) {
    $router->get("/", "Controller:handler"); // /movie/
    $router->get("/{id}", "Controller:handler"); // /movie/{id}
    $router->get("/{id}/photo", "Controller:handler"); // /movie/{id}/photo
});

/**
 * The nested group will be replaced by the latest group name (if any)
 */

$router->get("/user", "Controller:handler"); // /admin/user
```

#### Routing

Single requests methods

```php
$router->get("/", "Controller:handler");
$router->post("/", "Controller:handler");
$router->put("/", "Controller:handler");
$router->patch("/", "Controller:handler");
$router->delete("/", "Controller:handler");
$router->options("/", "Controller:handler");
```

Multiple requests methods

```php
$router->route(['GET', 'POST', 'PUT'], "/", "Controller:handler");
```

For a route that can be accessed using any method call

```php
$router->any("/", "Controller:handler");
```

#### Static Route

Regular string representing a URI. It will be compared directly against the path part of the current URL.

```php
$router->get("/about", "Controller:handler");
```

#### Dynamic Route

Contain dynamic parts which can vary per request.

```php
$router->get("/user/{id}", "Controller:handler");
```

#### Middlewares

Contain dynamic parts which can vary per request.

```php
$router->post("/user", "Controller:handler")->middleware("SurerLoki\Router\Middleware:handler");
```

#### License

This project is under MIT License. Please see the [License File](https://github.com/AdrianoLima19/router/blob/master/LICENSE) for more information.
