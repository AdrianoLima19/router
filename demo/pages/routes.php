<?php

$paginator = 1;
$count = 1;
$page = 1;
$limit = 10;

?>

<div class="container link-list">

    <ul class="list-group row justify-content-center align-router route-list">
        <li class="list-group-item">
            <h4>Routes</h4>
        </li>
    </ul>

    <table class="table">

        <thead>
            <tr>
                <th scope="col">Method</th>
                <th scope="col">Route</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($params->routes as $method) : ?>

                <?php foreach ($method as $route => $value) : ?>

                    <tr id="index-page">

                        <?php if ($count <= $limit) : ?>

                            <td data-page="<?php echo $page ?>">

                            <?php else : ?>

                            <td data-page="<?php echo ++$page;
                                            $count = 1;
                                            $paginator++ ?>">

                            <?php endif ?>

                            <?php echo $value['method'] ?? 'GET'; ?>

                            <?php $count++; ?>

                            </td>

                            <td data-page="<?php echo $page; ?>">
                                <?php echo $value['route'] ?>
                            </td>
                    </tr>

                <?php endforeach ?>

            <?php endforeach ?>

        </tbody>

    </table>

    <nav aria-label="Page navigation example">

        <ul class="pagination justify-content-center">

            <li class="page-item"><a class="page-link first-page" data-page="1">&laquo;</a></li>
            <?php for ($i = 1; $i <= $paginator; $i++) : ?>
                <li class="page-item"><a class="page-link id-page" href="1"><?php echo $i ?> </a></li>
            <?php endfor ?>
            <li class="page-item"><a class="page-link last-page" data-page="<?php echo $paginator ?>">&raquo;</a></li>

        </ul>

    </nav>

</div>

<script src="./assets/js/jquery.min.js"></script>
<script>
    $(document).ready(() => {

        listRoutes()
    })

    $('.first-page').click(function(event) {

        event.preventDefault();

        listRoutes($(this).data('page'))
    })

    $('.last-page').click(function(event) {


        event.preventDefault();

        listRoutes($(this).data('page'))
    })

    $(".id-page").click(function(event) {

        event.preventDefault();

        listRoutes($(this).text())
    });

    function listRoutes(boot) {

        $("#index-page td").each(function(index, element) {

            boot = boot || 1

            $(this).hide()

            if ($(this).data('page') == boot) {
                $(this).fadeIn(150)
            }
        });
    }
</script>
