<?php 
    require 'includes/config.php'; 
?>
<form action="login.php" method="post" align="center">
    <fieldset>
        <div class="form-group">
            <input autocomplete="off" autofocus class="form-control" name="email" placeholder="Email" type="text"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="password" placeholder="Password" type="password"/>
        </div>
        <div class="form-group">
            <button class="btn btn-default" type="submit">
                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                Log In
            </button>
        </div>
    </fieldset>
</form>
<div>
    or <a href="register.php">register</a> for an account
</div>

<?php
    // if user reached page via GET (as by clicking a link or via redirect)
    //if ($_SERVER["REQUEST_METHOD"] == "GET"){
        // else render form
      //  header('Location:login.php');
    //}
    //Insert registration info
    if(!empty($_POST)) {
        try {
            // Retrieve data
            $email = $_POST['email'];
            $password = $_POST['password'];
            $sql_select = "SELECT * FROM user WHERE email = '".$email."' AND password = '".$password."'";
            $stmt = $conn->query($sql_select);
            if(!test_input($email)) {
                echo "<h1>You must enter your email</h1>";
            }
            else if(!test_input($password)) {
                echo "<h1>You must enter your password</h1>";
            }
            else if($row = $stmt->fetch()) {
                $_SESSION["id"] = $row["id_user"];
                $_SESSION["password"] = $password;
                $_SESSION["logged_in"] = "YES";
                header('Location:myProfilePage.php');
            }
            //Otherwise, render index/homepage. Set seesion to be logged in
            else {
                echo "<h1>The email address or password is incorrect</h1>";
            }
        }
        catch(Exception $e) {
            die(var_dump($e));
        }
    }

    function test_input($data) {
        if(!isset($data) || trim($data) == '') {
            return false;
        }
        else {
            return true;
        }
    }
?>
