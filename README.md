# SurerLoki\Router

[![license](https://img.shields.io/github/license/AdrianoLima19/router)](https://github.com/AdrianoLima19/router/blob/master/LICENSE)
![php](https://img.shields.io/packagist/php-v/surerloki/router)
![version](https://img.shields.io/packagist/v/surerloki/router)
![CircleCI](https://img.shields.io/circleci/build/github/AdrianoLima19/router)
![Codecov](https://img.shields.io/codecov/c/gh/AdrianoLima19/router)

The router is a simple object-oriented library to handle HTTP requests.

## Features

- #### [Router](#router-methods)
  - [Basic Usage](#basic-usage)
  - [Routing methods](#routing-methods)
  - [Dynamic Routing](#dynamic-routes)
  - [Regular Expression Constraints](#regular-expression-constraints)
  - [Middleware](#middleware)
  - [Groups](#groups)
  - [Fallback Route](#fallback-route)
  - [Form Spoofing](#)
- #### [Request](#request-methods)
  - [Properties](#request-properties)
  - [Data](#request-data)
- #### [Response](#response-methods)
- #### [Redirect](#redirect-route)

## Requirements

- <a href="https://getcomposer.org/doc/01-basic-usage.md#package-versions" target="_blank">Composer</a>
- <a href="https://www.php.net/downloads" target="_blank">PHP</a> ^7.4
- [Rewrite URL](#configuring-htaccess)

## Installation

Installation is available via composer:

```sh
composer require surerloki/router 2.x
```

## Demo

Open the terminal on the project root folder and execute the commands below, after that the demo server will be available at <a href="http://localhost:8081" target="_blank">http://localhost:8081</a>:

```sh
cd vendor/surerloki/router/demo
php -S localhost:8081
```

## Configuring the htaccess

In the project's root folder, create an .htaccess file and add the following commands:

```apache
<IfModule mod_rewrite.c>
  RewriteEngine on
  RewriteRule ^$ public/ [L]
  RewriteRule (.*) public/$1 [L]
</IfModule>
```

Now, in the public folder, create another .htaccess and add the following commands, just replacing the `project_folder` with the name of the project folder:

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

## Router Methods

### Basic Usage

#### Routing

The route definition takes the following structure:

```php
$router->method($route, $callback);
$router->method($route, $handler);
```

Where:

- \$router is an instance of Router.
- method is an avaliable [routing method](#routing-methods).
- \$route is a path on the server.
- \$callback is executed when the route is matched.
- \$handler executes the controller when the route is matched.

#### Structure

The following example illustrates a basic routing structure:

```php
// Require autoload
require __DIR__ . "../vendor/autoload.php";

// Creates an instance of the Router
$router = new \SurerLoki\Router\Router();

// Define the namespaces and group name.
$router->namespace('Controller')
    ->middleware('Middleware')
    ->group();

// Respond to a GET request in the / route
$router->get('/', function () {
    echo "Hello World!";
});

// Respond to a POST request in the /user route
$router->post('/user', function ($req, $res) {
    $res->send("POST requested to /user");
});

// Respond to a PUT request in the /user/{id} route
$router->put('/user/{id}', function ($req, $res) {
    $res->send("PUT requested to user {$req->params->id}");
});

// Respond to a DELETE request in the /user/{id} route
$router->delete('/user/{id}', function ($req, $res) {
    $res->send("DELETE requested to user {$req->params->id}");
});

// Respond to ANY request in the /table/{user} route
$router->any('/table/{user}', function ($req, $res) {
    $res->send("{$req->method} requested to /table/{$req->params->id}");
});

// Will be executed when no other route matches the request
$router->fallback(function ($req, $res) {
    // Fallback aways have a $req->error
    $res->send("ERROR {$req->error}");
});

// Executes the routes
$router->run()
```

#### Start the server

Open the terminal on the project root folder and execute the commands below, after that the server will be available at <a href="http://localhost:8080" target="_blank">http://localhost:8080</a>:

```bash
cd public
php -S localhost:8080
```

### Routing Methods

The router allows registering routes that respond to any HTTP verb:

```php
$router->get('/', function (){});
$router->head('/', function (){});
$router->post('/', function (){});
$router->put('/', function (){});
$router->patch('/', function (){});
$router->delete('/', function (){});
$router->options('/', function (){});
```

But if the registered route needs to respond to several methods, `match` can be used:

```php
$router->match(['get', 'post', 'put'], "/", function () {
    // code...
});
```

Or `any` can be used if the registered route needs to respond to all HTTP verbs:

```php
$router->any("/", function () {
    // code...
});
```

### Dynamic Routes

When defining routes, it is sometimes necessary to obtain parameters from them:

```php
$router->get("'posts/{post}/comments/{comment}", function ($req) {
    /**
     * $req->params->post
     * $req->params->comment
     */
    // code...
});
```

### Regular Expression Constraints

The `where` method accepts the name of the parameter and a regular expression defining how the parameter should be constrained:

```php
get('user/{name}', function ($req) {
    // code...
})->where(['name' => '[a-zA-Z]+']);

get('user/{id}', function ($req) {
    // code...
})->where(['id' => '[0-9]+']);

get('user/{id}/{name}', function ($req) {
    // code...
})->where(['id' => '[0-9]+', 'name' => '[a-zA-Z]+']);
```

### Middleware

The middleware `before` will be executed before the route handling is processed:

```php
$router->get('/user', function () {
    // code...
})->before(function () {
    // code...
});
```

The middleware `after` will be executed after the route handling is processed:

```php
$router->get('/user', function () {
    // code...
})->after(function () {
    // code...
});
```

Both middleware methods can be linked to a route:

```php
$router->get('/user', function () {
    // code...
})->before(function () {
    // code...
})->after(function () {
    // code...
});
```

### Groups

When only a route is assigned in the group method, all the routes defined below will belong to this group, to remove or change the group, just redefine the method leaving it empty or giving it a new value:

```php
$router->group('/admin');

$router->get('/dash', function () { // /admin/dash
    // code...
});
$router->get('/option', function () { // /admin/option
    // code...
});

$router->group('/user');

$router->get('/shop', function () { // /user/blog
    // code...
});

$router->group();

$router->get('/blog', function () { // /blog
    // code...
});
```

When the `group` method receives a route and a callback, only the routes defined inside the method will be affected:

```php
$router->group('/user');

$router->group('/admin', function () use ($router) {

    $router->get('/dash', function () { // /admin/dash
        // code...
    });
});

$router->get('/panel', function () { // /user/panel

$router->group();

$router->group('/admin', function () use ($router) {

    $router->get('/option', function () { // /admin/option
        // code...
    });
});

$router->get('/blog', function () { // /blog
```

The method `group` can also share attributes across a large number of routes without needing to define those attributes on each individual route.

```php
// All routes inside this callback will have the same middleware method.
$router->group('/admin', function () use ($router) {

    $router->get('/dash', function () { // /admin/dash
        // code...
    });
    $router->get('/option', function () { // /admin/option
        // code...
    });
})->before(function () {
  // code...
});

$router->group(function () use ($router) {

    $router->get('/user/{id}', function () { // /admin/dash
        // code...
    });
    /**
      * If the route and the callback have the same methods,
      * the method on the route takes precedence.
      */
    $router->get('/blog/{id}', function () { // /admin/dash
        // code...
    })->where(['id' => '[a-zA-Z0-9]+']);
})->where(['id' => '[0-9]+']);
```

### Fallback Route

The `fallback` method will be executed when no other route matches the incoming request:

```php
$router->fallback(function ($req) {
    // The fallback route will always have a $req->error which is an HTTP error code
    // code...
});
```

### Form Method Spoofing

When defining PUT, PATCH or DELETE routes that are called from an HTML form, add a hidden \_method field to the form. The value sent with the \_method field will be used as the HTTP request method:

```html
<form action="/user" method="POST">
  <input type="hidden" name="_method" value="PUT" />
</form>
```

## Request Methods

### Request Properties

The method `baseUrl` receives the URL path that was assigned to the router when it was instantiated, if no path was assigned, by default this method is empty:

```php
$req->baseUrl
```

The method `method` corresponds to the HTTP method of the request:

```php
// GET /user
$req->method  // GET
```

The `format` method corresponds to the type of data sent with the request.

```php
// POST /register
// Content-Length: 56
// | {
// | 	"name": "john",
// | 	"email": "surerloki3379@gmail.com"
// | }
$req->format  // application/json
```

The method `uri` return the request path:

```php
// Route /table/client/{id}
// GET   /table/client/245
$req->uri // /table/client/245
```

### Request Data

The `query` method contains all queries in the request, by default this method is an empty object:

```php
// GET /shoes?order=desc&type=converse
$req->query->order  // desc
$req->query->type   // converse
```

The `params` method contains all the dynamic parameters of the route, by default this method is an empty object:

```php
// Route /user/{id}
// GET   /user/23
$req->params->id  // 23
```

The `body` method contains all the data sent in the request body, and can be filled out using html forms, json or Xml. By default this method is an empty object:

```php
// POST /register
// Content-Length: 56
// | {
// | 	"name": "john",
// | 	"email": "surerloki3379@gmail.com"
// | }
$req->body->name  // "john
$req->body->email // "surerloki3379@gmail.com"
```

The `error` method is only available within the fallback route and always has an HTTP error code:

```php
//GET /unknow
$req->error // 404
```

## Response Methods

The method `send` returns a simple string:

```php
$res->send('Hello', '<br>');
$res->send('<span>World</span>');
$res->send(
    '<h4>Lorem ipsum</h4>',
    '<br>',
    'Lorem ipsum dolor sit amet consectetur adipisicing elit.'
);
```

The `render` method includes the files provided in order:

```php
$res->render(
  '/demo/header.php',
  '/demo/nav.php',
  '/demo/section.php',
  '/demo/article.php',
  '/demo/footer.php',
);
```

The method `json` sends a JSON response:

```php
$res->json(
  [
    'status' => 200,
    'message' => 'messsage test',
    'data' => [
      'name' => 'test'
    ]
  ],
);
```

### Redirect Route

If the route needs to redirect to another URI, the method `redirect` provides a convenient shortcut for performing a simple redirect:

```php
$router->get("/redirect", function ($req ,$res, $server) {
    $server->redirect('/');
});
```

The `route` can be used to redirect the route and customize the status code:

```php
$router->get("/redirect-to-login", function ($req ,$res, $server) {
    $server->route('/login', 301);
});
```

## License

This project is under MIT License. Please see the [license file](https://github.com/AdrianoLima19/router/blob/master/LICENSE) for more information.
