<?php

$total = 0;
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

                    <?php $total++ ?>

                    <?php if ($count <= $limit) : ?>

                        <tr class="index-page" data-page="<?php echo $page ?>">


                        <?php else : ?>
                        <tr class="index-page" data-page="<?php echo ++$page;
                                                            $count = 1;
                                                            $paginator++ ?>">

                        <?php endif ?>

                        <td><?php echo $value['method'] ?? 'GET'; ?><?php $count++; ?></td>

                        <td><?php echo $value['route'] ?></td>

                        </tr>

                    <?php endforeach ?>

                <?php endforeach ?>

                <?php

                do {
                    $total++;
                    echo '<tr class="index-page" data-page="' . $paginator . '">
                    <td class="hide">HIDE</td>
                    <td class="hide">hide</td>
                    </tr>';
                } while ($total % 10);
                ?>

        </tbody>

    </table>

    <nav aria-label="Page navigation example">

        <ul class="pagination justify-content-center">

            <li class="page-item"><a class="page-link first-page" data-page="1">&laquo;</a></li>
            <?php for ($i = 1; $i <= $paginator; $i++) : ?>
                <li class="page-item"><a class="page-link id-page" href="#" data-page="<?php echo $i ?>"><?php echo $i ?> </a></li>
            <?php endfor ?>
            <li class="page-item"><a class="page-link last-page" data-page="<?php echo $paginator ?>">&raquo;</a></li>

        </ul>

    </nav>

</div>

<script>
    $(document).ready(() => {

        $(".hide").css({
            'opacity': '0',
            'border-color': 'transparent'
        });

        $('.first-page').parent().next().addClass('active')

        listRoutes()
    })

    $('.first-page').click(function(event) {

        event.preventDefault();

        $(".id-page").parent().removeClass('active')

        $(this).parent().next().addClass('active')

        listRoutes($(this).data('page'))
    })

    $('.last-page').click(function(event) {

        event.preventDefault();

        $(".id-page").parent().removeClass('active')

        $(this).parent().prev().addClass('active')

        listRoutes($(this).data('page'))

        listRoutes($(this).data('page'))
    })

    $(".id-page").click(function(event) {

        event.preventDefault();

        listRoutes($(this).data('page'))

        $(".id-page").parent().removeClass('active')

        $(this).parent().addClass('active')
    });

    function listRoutes(boot) {

        $(".index-page").each(function() {

            boot = boot || 1

            if (boot == 1) {

                $('.first-page').parent().addClass('disabled')
            } else {

                $('.first-page').parent().removeClass('disabled')
            }

            if (boot == $('.last-page').data('page')) {

                $('.last-page').parent().addClass('disabled')
            } else {

                $('.last-page').parent().removeClass('disabled')
            }

            // if (boot == $(".id-page").data('page')) {
            //     $(".id-page").data('page').parent().removeClass('active')
            // }

            if ($(this).data('page') != boot) {
                $(this).hide()
            } else {
                $(this).show()
            }
        });
    }
</script>
