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
                        <li class="active"><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    </ul>
                </div>
        </nav>
        <form action="login.php" method="post" align="center">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-5">
                    </div>
                    <div class="col-md-2">
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
                    </div>
                    <div class="col-md-5">
                    </div>
                </div>
            </div>
        </form>
    </body>

    <?php
        $_SESSION["logged_in"]="NO";
        $_SESSION["id"] = "";
        $_SESSION["user_type"]="";
        //Insert registration info
        if(!empty($_POST)) {
            try {
                // Retrieve data
                $email =$_POST['email'];
                $password = $_POST['password'];
                $sql_select = "SELECT * FROM user WHERE email = '".$email."' AND password = '".sha1($password)."'";
                $admin_select = "SELECT * FROM admin WHERE email = '".$email."' AND password = '".sha1($password)."'";
                $stmt = $conn->query($sql_select);
                $adminsql = $conn->query($admin_select);
                if(!test_input($email)) {
                    echo "<script>alert('You must enter your email');</script>";
                }
                else if(!test_input($password)) {
                    echo "<script>alert('You must enter your password');</script>";
                }
                else if($row = $stmt->fetch()) {
                    $_SESSION["user_type"] = "USER";
                    $_SESSION["id"] = $row["id_user"];;
                    $_SESSION["logged_in"] = "YES";
                    header('Location:homepage.php');
                }
                else if($row = $adminsql->fetch())
                {
                    $_SESSION["user_type"] = "ADMIN";
                    $_SESSION["id"] = $row["id_admin"];;
                    $_SESSION["logged_in"] = "YES";
                    header('Location:admin.php');
                }
                //Otherwise, render index/homepage. Set seesion to be logged in
                else {
                    echo "<script>alert('The email address or password is incorrect');</script>";
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
