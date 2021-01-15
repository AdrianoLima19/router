<div class="container">

    <div class="form-dump">

        <!-- <?php
                var_dump([
                    "Method" => $params->request->method,
                    "Query" => $params->request->query
                ]);
                ?> -->
        <p>
            <a class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Raw</a>
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">Pretty</button>
        </p>
        <div class="row">
            <div class="col">
                <div class="collapse show" id="multiCollapseExample1">
                    <div class="card card-body">
                        <?php
                        var_dump([
                            "Method" => $params->request->method,
                            "Form" => $params->request->query
                        ]);
                        ?>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="collapse show" id="multiCollapseExample2">
                    <div class="card card-body">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Method</th>
                                    <th scope="col">Blog_ID</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row"><?php echo $params->request->method; ?></th>
                                    <td><?php echo $params->request->query->blog_id ?? null; ?></td>
                                    <td><?php echo $params->request->query->created_at ?? null; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <form method="get">

        <div class="form-group">
            <label for="exampleFormControlInput1">Blog</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" name="blog_id" value="<?php echo 'skroceta6JQfISZ0oVMc' ?>">
        </div>

        <div class="form-group">
            <label for="exampleFormControlInput1">Date</label>
            <?php date_default_timezone_set('America/Sao_paulo'); ?>
            <input type="text" class="form-control" id="exampleFormControlInput1" name="created_at" value="<?php echo date('Y-m-d H:i:s'); ?>">
        </div>

        <div class="form-group">
            <input type="submit" class="form-control" id="submitbutton" value="Seach ID">
        </div>

    </form>

</div>
