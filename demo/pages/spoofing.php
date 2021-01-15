<div class="container">

    <div class="form-dump">

        <!-- <?php
                var_dump([
                    "Method" => $params->request->method,
                    "Form" => $params->request->body
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
                            "Form" => $params->request->body
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
                                    <th scope="col">first_name</th>
                                    <th scope="col">last_name</th>
                                    <th scope="col">email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row"><?php echo $params->request->method; ?></th>
                                    <td><?php echo $params->request->body->first_name ?? null; ?></td>
                                    <td><?php echo $params->request->body->last_name ?? null; ?></td>
                                    <td><?php echo $params->request->body->email ?? null; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="post">

        <div class="form-group">
            <label for="exampleFormControlInput1">First Name</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" name="first_name" value="Lidia">
        </div>

        <div class="form-group">
            <label for="exampleFormControlInput1">Last Name</label>
            <input type="text" class="form-control" id="exampleFormControlInput1" name="last_name" value="Benton">
        </div>

        <div class="form-group">
            <label for="exampleFormControlInput1">Email</label>
            <input type="email" class="form-control" id="exampleFormControlInput1" name="email" value="name@example.com">
        </div>

        <div class="form-group">

            <label for="exampleFormControlSelect2">Methods</label>
            <select multiple class="form-control" name="_method" id="exampleFormControlSelect2">
                <option selected>POST</option>
                <option>PUT</option>
                <option>PATCH</option>
                <option>DELETE</option>
            </select>

        </div>

        <div class="form-group">
            <input type="submit" class="form-control" id="submitbutton" value="Send">
        </div>

    </form>

</div>
