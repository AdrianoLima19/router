<div class="container">

    <div class="form-dump">

        <?php
        var_dump([
            "Method" => $params->request->method,
            "Form" => $params->request->body
        ]);
        ?>

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
            <input type="submit" class="form-control" id="submitbutton" value="Send">
        </div>

    </form>

</div>
