# SurerLoki\Router

[![license](https://img.shields.io/github/license/AdrianoLima19/router)](https://github.com/AdrianoLima19/router/blob/master/LICENSE)
![php](https://img.shields.io/packagist/php-v/surerloki/router)
![version](https://img.shields.io/packagist/v/surerloki/router)

Router is a simple object-oriented library to handle HTTP routes.

## Features

- Can be used with all [HTTP methods](https://en.wikipedia.org/wiki/Hypertext_Transfer_Protocol#Request_methods)
- [Basic Usage](#basic-usage)
- [Single requests methods as `get()`, `post()`, `put()`, …](#route-methods)
- [Dynamic routing](#route-parameters)
- [Regular Expression Constraints](#regular-expression-constraints)
- [Middleware](#middleware)
- [Fallback Route](#fallback-route)
- [Form Spoofing](#form-method-spoofing)

## Requirements

<ul style="list-style:circle;padding-left:1.5rem;margin-left:0;">
<li><a href="https://getcomposer.org/doc/01-basic-usage.md#package-versions" target="_blank">Composer</a></li>
<li><a href="https://www.php.net/downloads" target="_blank">PHP</a> 7.3^</li>
<li><a href="#enabling-htaccess">Rewrite URL</a></li>
</ul>

## Installation

Installation is available via Composer:

```json
"surerloki/router": "1.x"
```

or run

```sh
composer require surerloki/router 1.x
```

## Enabling htaccess

On the root project folder create a .htaccess file and add the following commands:

```apache
<IfModule mod_rewrite.c>
  RewriteEngine on
  RewriteRule ^$ public/ [L]
  RewriteRule (.*) public/$1 [L]
</IfModule>
```

Now in the public folder create another .htaccess and add the following commands just substituting the project_folder for the actual project folder:

```apache
<IfModule mod_rewrite.c>
  Options -Multiviews
  RewriteEngine On
  RewriteBase /project_folder/public
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule  ^(.+)$ index.php?uri=$1 [QSA,L]
</IfModule>
```

## Basic Usage

#### Basic Routing

The route definition takes the following structure:

```php
$router->method($route, $callback);
$router->method($route, $handler);
```

Where:

- \$router is an instance of Router.
- method is an [HTTP request](https://en.wikipedia.org/wiki/Hypertext_Transfer_Protocol#Request_methods).
- \$route is a path on the server.
- \$callback is executed when the route is matched.
- \$handler execute the controller when the route is matched.

#### Basic Structure

The following example illustrate defining a basic route structure:

```php
// Require autoload
require __DIR__ . "../vendor/autoload.php";

// Create Router class
$router = new \SurerLoki\Router\Router();

// Respond to a GET request to the / route
$router->get('/', function () {
    echo "Hello World!";
});

// Respond to a POST request to the /user route
$router->post('/user', function () {
    echo "POST requested to /user";
});

// Respond to a PUT request to the /user/{id} route
$router->put('/user/{id}', function ($data) {
    echo "PUT requested to user {$data['id']}";
});

// Respond to a DELETE request to the /user/{id} route
$router->delete('/user/{id}', function ($data) {
    echo "DELETE requested to user {$data['id']}";
});

// Will be executed when no other route matches the request
$router->fallback(function ($data) {
    // Fallback aways return a $data['error']
    echo "ERROR {$data['error']}";
});

// Executes the routes
$router->run()
```

## Route Methods

The router allows the registered route to respond any HTTP request method:

```php
$router->get('/route', function (){});
$router->post('/route', function (){});
$router->put('/route', function (){});
$router->patch('/route', function (){});
$router->delete('/route', function (){});
$router->options('/route', function (){});
```

But if the registered route needs to respond to multiple methods `match` can be used:

```php
$router->match(['get', 'post', 'put'], "/", function () {
    // code ...
});
```

Or `any` can be used if the registered route needs to respond to all HTTP methods:

```php
$router->any("/", function () {
    // code ...
});
```

## Route Parameters

Sometimes the route needs to capture segmets of the URI:

```php
$router->get("'posts/{post}/comments/{comment}", function ($data) {
    /**
     * $data['post']
     * $data['comment']
     */
    // code ...
});
```

### Regular Expression Constraints

The `where` method accepts the parameter and a regex defining how the parameter should be constrained:

```php
get('user/{name}', function ($data) {
    //
})->where('name', '[a-zA-Z]+');

get('user/{id}', function ($data) {
    //
})->where('id', '[0-9]+');

get('user/{id}/{name}', function ($data) {
    //
})->where(['id' => '[0-9]+', 'name' => '[a-zA-Z]+']);
```

### Namespaces

Assign the same namespace to controllers using the namespace method:

```php
$router->namespace("SurerLoki\Router\Demo");
$router->get($route, 'Web:route'); // Namespace\Path\To\Web -> route()
```

### Groups

The `group` method can be used to nest urls that have the same starting path

```php
$router->group("/admin");
$router->get('/dash', 'Web:route'); // /admin/dash
$router->get('/report', 'Web:route'); // /admin/report
$router->get('/user', 'Web:route'); // /admin/user
```

### Nested Groups

Groups can be nested with other groups and/or sets of urls

```php
// set the group
$router->group('/user');

$router->get('/{name}', $handler); // /user/{name}

$router->group("/admin", function () use ($router) {
    // the nested group will be only applied here
    $router->get('/dash', $handler); // /admin/dash

    $router->group("/user", function () use ($router) {
        $router->get('/table', $handler); // /admin/user/table
    });

    $router->get('/info', $handler); // /admin/info
});

// get the last applied group if any
$router->get('/{id}', $handler); // /user/{id}

```

### Middleware

The `before` method will be executed before the route handling is processed.

```php
$router->get('/user', function () {
    // code ...
})->before(function () {
    // code ...
});
```

The `after` method will be executed after the route handling is processed.

```php
$router->get('/user', function () {
    // code ...
})->after(function () {
    // code ...
});
```

Both middlewares can be hooked to a route

```php
$router->get('/user', function () {
    // code ...
})->before(function () {
    // code ...
})->after(function () {
    // code ...
});
```

### Redirect

If the route redirects to another URI, the method `redirect` provides a convenient shortcut for performing a simple redirect:

```php
$router->get("/redirect", function () {
    $router->redirect('/');
});
```

The `route` can be used to redirect the route and customize the status code:

```php
$router->get("/redirect-to-login", function () {
    $router->route('/login', 301);
});
```

### Fallback Route

The `fallback` method will be executed when no other route matches the incoming request:

```php
$router->fallback(function ($data) {
    // $data['error']
    // code ...
});
```

### Form Method Spoofing

When defining PUT, PATCH or DELETE routes that are called from an HTML form, add a hidden \_method field to the form. The value sent with the \_method field will be used as the HTTP request method:

```html
<form action="/user" method="POST">
  <input type="hidden" name="_method" value="PUT" />
</form>
```

### License

This project is under MIT License. Please see the [License File](https://github.com/AdrianoLima19/router/blob/master/LICENSE) for more information.
