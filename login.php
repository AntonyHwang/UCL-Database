
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
<?php
    require("../includes/config.php");
    // DB connection info
    //TODO: Update the values for $host, $user, $pwd, and $db
    //using the values you retrieved earlier from the Azure Portal.
    $host = "eu-cdbr-azure-west-a.cloudapp.net";
    $user = "bd38b99b177044";
    $pwd = "5e59f1c8";
    $db = "blogster";
    // Connect to database.
    try {
        $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
    catch(Exception $e){
        die(var_dump($e));
    }
    //Insert registration info
    if(!empty($_POST)) {
        try {
            // Retrieve data
            $email = $_POST['email'];
            $password = $_POST['password'];
            $sql_select = "SELECT user_id FROM user WHERE (email = '".$email."' AND password = '".$password."')";
            $stmt = $conn->query($sql_select);
            $registrants = $stmt->fetchAll();
            if(!test_input($email)) {
                echo "<h2>You must enter your email</h2>";
            }
            else if(!test_input($password)) {
                echo "<h2>You must enter your password</h2>";
            }
            else if(count($registrants) == 0) {
                echo "<h2>The email address or password is incorrect</h2>";
            } 
            //Otherwise, render index/homepage. Set seesion to be logged in
            else {
                $_SESSION['logged_in'] = 'YES';
                $_SESSION['id'] = $stmt['user_id'];
                redirect("/index.php");
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
<div>
    or <a href="register_form.php">register</a> for an account
</div>
