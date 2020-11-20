<div class="container">

    <div class="form-dump">

        <?php
        var_dump([
            "Method" => $params->request->method,
            "Query" => $params->request->query
        ]);
        ?>

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
