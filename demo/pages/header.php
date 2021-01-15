<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Router</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/css/global.css">

    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/js/popper.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-light bg-light align-content-center">
        <a class="navbar-brand" href="/">
            Bootstrap
        </a>
        <?php
        $code = $params->request->error ?? http_response_code();
        switch ($code) {
            case 200:
                echo '<h4 style="padding:5px"><span class="badge badge-success">200 Ok</span></h4>';
                break;
            case 301:
                echo '<h4 style="padding:5px"><span class="badge badge-info">301 Permanent Redirect</span></h4>';
                break;
            case 404:
                echo '<h4 style="padding:5px"><span class="badge badge-danger">404 Not Found</span></h4>';
                break;
            case 405:
                echo '<h4 style="padding:5px"><span class="badge badge-danger">405 Method Not Allowed</span></h4>';
                break;
            default:
                echo '<h4 style="padding:5px"><span class="badge badge-secondary">' . $code . ' Unregistered code</span></h4>';
        }
        ?>
    </nav>
    <h1>Router Demo</h1>
