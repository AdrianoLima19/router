<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <?php

        include "../vendor/autoload.php";

        $router = new \SurerLoki\Router\Router();

        $router->namespace("SurerLoki\Router\Demo");

        $router->get('/', 'Web:home');
        $router->get('/admin', 'Web:admin');

        $router->any('/user', 'Web:user');

        $router->fallback(function ($data) {

            echo "<h1>ERROR {$data['error']}</h1>";

            echo '<a href="/">return</a>';
        });

        $router->run();

        ?>
    </header>

    <div class="redirects">
        <a href="/">home</a>
        <a href="/admin">admin</a>

        <a href="/unknow">fallback</a>

        <form action="/" method="post">
            <input type="submit" value="method not allowed" class="link">
        </form>

        <form action="/user" method="post" autocomplete="off">
            <input list="methods" name="_method" required class="field">
            <datalist id="methods">
                <option selected value="POST"></option>
                <option value="PUT"></option>
                <option value="PATCH"></option>
                <option value="DELETE"></option>
            </datalist>
            <input type="text" name="first_name" value="John" class="field">
            <input type="text" name="last_name" value="Doe" class="field">
            <input type="text" name="email" value="johndoe68@gmail.com" class="field">
            <input type="submit" value="send form">
        </form>
    </div>
</body>

</html>