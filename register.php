<?php 
    require 'includes/config.php'; 
?>
<html>
    <head>
        <link rel="stylesheet" type="css" href="./css/register.css">
    </head>
    <body>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <!-- logo -->
                <div class="navbar-header">
                    <a href="#" class="navbar-brand">BLOGSTER</a>
                </div>
                <!-- menu items -->
                <div>
                    <ul class="nav navbar-nav">
                        <li><a href="login.php">Login</a></li>
                        <li class="active"><a href="register.php">Register</a></li>
                    </ul>
                </div>
        </nav>
    </body>
    <form action="register.php" method="post" align="center">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5">
                </div>
                <div class="col-md-2">
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
                            <input type="radio" name="gender" value="Male" checked> Male
                            <input type="radio" name="gender" value="Female"> Female
                            <input type="radio" name="gender" value="Other"> Other
                        </div><br>
                        <div class="form-group" align="left">
                            Birthday
                            <?php
                            $currentdate = date('Y-m-d');
                            ?>
                            <input type="date" class="form-control" name="birthday" placeholder="YYYY-MM-DD" max="<?php echo $currentdate ?>">
                        </div><br>
                        <div class="form-group">
                            <button class="btn btn-default" type="submit" style="vertical-align:left; float: center">
                                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                                Register
                            </button>
                            <br>
                            <br>
                            <button class="btn btn-default" type="reset" style="vertical-align:left; float: center">
                                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                                Reset
                            </button>
                        </div><br>
                    </fieldset>
                </div>
                <div class="col-md-5">
                </div>
            </div>
        </div>
    </form>
<?php
    
    if(!empty($_POST)) {
        try {
            // Retrieve data
            $first_name = strtolower($_POST['first_name']);
            $surname = strtolower($_POST['surname']);
            $email = strtolower($_POST['email']);
           
            $password = $_POST['password'];
            $password_confirm = $_POST['confirmation'];
            $gender = $_POST['gender'];
            $dob = $_POST['birthday'];
            $sql_select = "SELECT * FROM user WHERE email = '".$email."'";
            $stmt = $conn->query($sql_select);
            $registrants = $stmt->fetchAll();

            //  Data Validation
            if(!test_input($first_name)) {
                echo "<script>alert('You must enter your first name');</script>";
            }
            else if (!preg_match("/^[a-zA-Z ]*$/",$first_name)) {
                echo "<script>alert('First name incorrect format');</script>";
            }
            else if(!test_input($surname)) {
                echo "<script>alert('You must enter your surname');</script>";
            }
            else if (!preg_match("/^[a-zA-Z ]*$/",$surname)) {
                echo "<script>alert('Surname incorrect format');</script>";
            }
            else if(!test_input($email)) {
                echo "<script>alert('You must enter your email');</script>";
            }
            else if(!test_input($password)) {
                echo "<script>alert('You must enter a valid password');</script>";
            }
            else if($password != $password_confirm) {
                echo "<script>alert('Password does not match');</script>";
            }
            else if(count($registrants) != 0) {
                echo "<script>alert('Email already registered');</script>";
            } 
             else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<script>alert('Invalid email format');</script>";
            }
         
         //Insert registration info
            else {
                $sql_insert = "INSERT INTO user (first_name, surname, email, password, gender, dob, privacy_setting)VALUES ('".$first_name."','".$surname."','".$email."','".sha1($password)."','".$gender."','".$dob."', 0);";
                $sql_get_id = "SELECT id_user FROM user WHERE email = '".$email."';";
                $stmt = $conn->prepare($sql_insert);
                $stmt->execute();
                $stmt = $conn->prepare($sql_get_id);
                $stmt->execute();
                $rows = $stmt->fetch();
                mkdir(getcwd()."/uploads/".$rows["id_user"]);
                $default_profile_pic = getcwd().'/uploads/default-profile.jpg';
                $user_profile_pic = getcwd().'/uploads/'.$rows["id_user"].'/profile.jpg';
                copy($default_profile_pic, $user_profile_pic);
                $_SESSION["id"] = $rows["id_user"];
                $_SESSION["logged_in"] = "YES";
                $_SESSION["user_type"] = "USER";
                header('Location:myProfilePage.php');
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
