<ul class="nav justify-content-center">

    <li class="nav-item footer-links">

        <?php if ($params->request->uri == '/') : ?>
            <a class="nav-link disabled" href="./">home</a>
        <?php else : ?>
            <a class="nav-link active" href="./">home</a>
        <?php endif ?>

        <?php if ($params->request->uri == '/routes') : ?>
            <a class="nav-link disabled" href="./routes">routes</a>
        <?php else : ?>
            <a class="nav-link active" href="./routes">routes</a>
        <?php endif ?>

        <?php if ($params->request->uri == '/form') : ?>
            <a href="./form" class="nav-link disabled">form</a>
        <?php else : ?>
            <a href="./form" class="nav-link active">form</a>
        <?php endif ?>

        <?php if ($params->request->uri == '/spoofing') : ?>
            <a href="./spoofing" class="nav-link disabled">spoofing</a>
        <?php else : ?>
            <a href="./spoofing" class="nav-link active">spoofing</a>
        <?php endif ?>

        <?php if ($params->request->uri == '/class-info') : ?>
            <a href="./class-info" class="nav-link disabled">Info</a>
        <?php else : ?>
            <a href="./class-info" class="nav-link active">Info</a>
        <?php endif ?>

        <?php if ($params->request->uri == '/blog') : ?>
            <a href="./blog" class="nav-link disabled">blog</a>
        <?php else : ?>
            <a href="./blog" class="nav-link active">blog</a>
        <?php endif ?>

        <?php if ($params->request->uri == '/middleware') : ?>
            <a href="./middleware" class="nav-link disabled">middleware</a>
        <?php else : ?>
            <a href="./middleware" class="nav-link active">middleware</a>
        <?php endif ?>

    </li>

    <li class="nav-item footer-links">&copy; 2020 SurerLoki3379</li>

</ul>

</body>

</html>
