<?php 
    require 'includes/config.php'; 
    include_once('adminheader.php');
?>
<html>
<form action="admin_register.php" method="post" align="center">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
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
                       
                       
                        <div class="form-group">
                            <button class="btn btn-default" type="submit" style="vertical-align:left; float: center">
                                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                                Register
                            </button>
                            
                        </div><br>
                    </fieldset>
                </div>
                <div class="col-md-4">
                </div>
            </div>
        </div>
    </form>
<?php
    //Insert registration info
    if(!empty($_POST)) {
        try {
            // Retrieve data
            $first_name = strtolower($_POST['first_name']);
            $surname = strtolower($_POST['surname']);
            $email = strtolower($_POST['email']);
            $password = $_POST['password'];
            $password_confirm = $_POST['confirmation'];
            $sql_select = "SELECT * FROM admin WHERE email = '".$email."'";
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
                $sql_insert = "INSERT INTO admin (first_name, last_name, email, password)VALUES ('".$first_name."','".$surname."','".$email."','".sha1($password)."');";
                $stmt = $conn->prepare($sql_insert);
                $stmt->execute();
                header('Location:All_Users.php');
               
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
