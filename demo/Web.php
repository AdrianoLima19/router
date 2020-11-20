<?php

namespace SurerLoki\Router\Demo;

class Web
{
    public function home($req, $res, $server)
    {
        $res->params([
            'request' => $req
        ]);

        $res->render(
            '/demo/pages/header.php',
            '/demo/pages/nav.php',
            '/demo/pages/footer.php',
        );
    }

    public function route($req, $res, $server)
    {
        $res->params([
            'request' => $req,
            'routes' => $server->list()
        ]);

        $res->render(
            '/demo/pages/header.php',
            '/demo/pages/nav.php',
            '/demo/pages/routes.php',
            '/demo/pages/footer.php',
        );
    }

    public function form($req, $res)
    {
        $res->params([
            'request' => $req,
        ]);

        $res->render(
            '/demo/pages/header.php',
            '/demo/pages/nav.php',
            '/demo/pages/form.php',
            '/demo/pages/footer.php',
        );
    }

    public function blog($req, $res)
    {
        $res->params([
            'request' => $req,
        ]);

        $res->render(
            '/demo/pages/header.php',
            '/demo/pages/nav.php',
            '/demo/pages/query.php',
            '/demo/pages/footer.php',
        );
    }

    public function spoofing($req, $res)
    {
        $res->params([
            'request' => $req,
        ]);

        $res->render(
            '/demo/pages/header.php',
            '/demo/pages/nav.php',
            '/demo/pages/spoofing.php',
            '/demo/pages/footer.php',
        );
    }

    public function info($req, $res, $server)
    {
        $res->params([
            'request' => $req,
            'response' => get_class_methods($res),
            'server' => get_class_methods($server)
        ]);

        $res->render(
            '/demo/pages/header.php',
            '/demo/pages/nav.php',
            '/demo/pages/info.php',
            '/demo/pages/footer.php',
        );
    }

    public function error($req, $res, $server)
    {
        $res->params([
            'request' => $req,
        ]);

        $res->render(
            '/demo/pages/header.php',
            '/demo/pages/nav.php',
            '/demo/pages/error.php',
            '/demo/pages/footer.php',
        );
    }
}
