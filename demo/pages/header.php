<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Router</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <style>
        body {
            overflow-x: hidden;
        }

        h1,
        h2 {
            padding-top: 4rem;
            text-align: center;
        }

        h2 {
            padding-bottom: 2rem;
        }

        .list-group-item-action {
            margin: 1rem 0;
        }

        .link-list {
            margin-top: 3rem;
            margin-bottom: 3rem;
        }

        .footer-links {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .route-list {
            width: 100%;
        }

        .align-router {
            text-align: center;
        }

        .table tr td:first-child {
            width: 50%;
        }

        .form-dump {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .container-info .card {
            margin: 1rem;
        }

        .container-info {
            width: 90%;
            margin: 2rem auto;
        }
    </style>
</head>

<body>
    <!-- Image and text -->
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
            case 404:
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
