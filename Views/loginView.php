<div class="row justify-content-center">
    <div class=" col-lg-5 col-md-7 col-11 mt-5 p-2 border shadow bg-secondary bg-opacity-10 rounded">
        <form class="" method="post" action="<?php echo $controller->getUrl("index.php?controller=Login")?>">
            <h4>Login:</h4>
            <div class="input-group">
                <label class="input-group-text bg-primary bg-opacity-10" for="loginemail">E-Mail:</label>
                <input class="form-control" id="loginemail" name="loginemail" type="text">
            </div>
            <div class="input-group my-2">
                <label class="input-group-text bg-primary bg-opacity-10" for="loginpass">Password:</label>
                <input class="form-control" id="loginpass" name="loginpass" type="password">
            </div>
            <input class="btn btn-primary opacity-75 text-opacity-100" name="loginsubmit" type="submit" value="login">
        </form>
    </div>
</div>