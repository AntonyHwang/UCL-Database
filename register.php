<?php ob_start(); 
    session_start();
?>
<html>
    <head>
        <link rel="stylesheet" type="css" href="./css/register.css">
    </head>
    <form action="register.php" method="post" align="center">
    <fieldset>
        <div class="form-group">
            <input autofocus class="form-control" name="first_name" id="first_name" placeholder="First Name" type="text" size="30"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="surname" id="surname" placeholder="Surname" type="text" size="30"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="email" id="email" placeholder="Email" type="text" size="30"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="password" id="password" placeholder="Password" type="password" size="30"/>
        </div>
        <div class="form-group">
            <input class="form-control" name="confirmation" id="confirmation" placeholder="Confirm Password" type="password" size="30"/>
        </div><br>
        <div class="form-group" align="left">
            <label style="font-size: 12px;">
            <input type="radio" name="gender" value="male" checked> Male
            <input type="radio" name="gender" value="female"> Female
            <input type="radio" name="gender" value="other"> Other
        </div><br>
        <div class="form-group" align="left">
            <label style="font-size: 12px;">
            Birthday
            <input type="date" name="bday" placeholder="dd/mm/yyyy">
        </div><br>
        <div class="form-group">
            <button class="btn btn-default" type="submit" style="vertical-align:left; float: center">
                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                Register
            </button>
            <button class="btn btn-default" type="reset" style="vertical-align:left; float: center">
                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                Reset
            </button>
        </div><br>
        <div>
            or <a href="login.php">log in</a> for an account
        </div>
    </fieldset>
</form>
<?php
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
            $first_name = $_POST['first_name'];
            $surname = $_POST['surname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $password_confirm = $_POST['confirmation'];
            $gender = $_POST['gender'];
            $dob = $_POST['bday'];
            $sql_select = "SELECT * FROM user WHERE email = '".$email."'";
            $stmt = $conn->query($sql_select);
            $registrants = $stmt->fetchAll();
            if(!test_input($first_name)) {
                echo "<h2>You must enter your first name</h2>";
            }
            else if(!test_input($surname)) {
                echo "<h2>You must enter your surname</h2>";
            }
            else if(!test_input($email)) {
                echo "<h2>You must enter your email</h2>";
            }
            else if(!test_input($password)) {
                echo "<h2>You must enter a valid password</h2>";
            }
            else if($password != $password_confirm) {
                echo "<h2>Password does not match</h2>";
            }
            else if(count($registrants) != 0) {
                echo "<h2>Email already registered</h2>";
            } else {
                $sql_insert = "INSERT INTO user (first_name, surname, email, password, gender, dob)VALUES (?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql_insert);
                $stmt->bindValue(1, $first_name);
                $stmt->bindValue(2, $surname);
                $stmt->bindValue(3, $email);
                $stmt->bindValue(4, $password);
                $stmt->bindValue(5, $gender);
                $stmt->bindValue(6, date("Y-m-d H:i:s",$dob));
                $stmt->execute();
                echo "<h3>Your're registered!</h3>";
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
</html>
