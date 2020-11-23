<div class="container link-list">

    <ul class="list-group list-group-horizontal row justify-content-center align-items-center">

        <?php if ($params->request->uri == '/') : ?>
            <a href="./" class="list-group-item list-group-item-action text-center disabled">Home</a>
        <?php else : ?>
            <a href="./" class="list-group-item list-group-item-action text-center">Home</a>
        <?php endif ?>

        <?php if ($params->request->uri == '/routes') : ?>
            <a href="./routes" class="list-group-item list-group-item-action text-center disabled">routes</a>
        <?php else : ?>
            <a href="./routes" class="list-group-item list-group-item-action text-center">routes</a>
        <?php endif ?>

        <?php if ($params->request->uri == '/form') : ?>
            <a href="./form" class="list-group-item list-group-item-action text-center disabled">form</a>
        <?php else : ?>
            <a href="./form" class="list-group-item list-group-item-action text-center">form</a>
        <?php endif ?>

        <?php if ($params->request->uri == '/spoofing') : ?>
            <a href="./spoofing" class="list-group-item list-group-item-action text-center disabled">spoofing</a>
        <?php else : ?>
            <a href="./spoofing" class="list-group-item list-group-item-action text-center">spoofing</a>
        <?php endif ?>

        <?php if ($params->request->uri == '/class-info') : ?>
            <a href="./class-info" class="list-group-item list-group-item-action text-center disabled">Info</a>
        <?php else : ?>
            <a href="./class-info" class="list-group-item list-group-item-action text-center">Info</a>
        <?php endif ?>

        <?php if ($params->request->uri == '/blog') : ?>
            <a href="./blog" class="list-group-item list-group-item-action text-center disabled">blog</a>
        <?php else : ?>
            <a href="./blog" class="list-group-item list-group-item-action text-center">blog</a>
        <?php endif ?>

        <?php if ($params->request->uri == '/middleware') : ?>
            <a href="./middleware" class="list-group-item list-group-item-action text-center disabled">middleware</a>
        <?php else : ?>
            <a href="./middleware" class="list-group-item list-group-item-action text-center">middleware</a>
        <?php endif ?>

        <a href="./unknow" class="list-group-item list-group-item-action text-center">trigger 404</a>

        <form style="width:100%;" action="./get" method="post">
            <input type="submit" value="trigger 405" class="list-group-item list-group-item-action text-center">
        </form>

    </ul>

</div>
