<?php 
    require 'includes/config.php'; 
    include_once('header.php');

    echo "<title> Account Settings</title>";

    //Insert registration info
    try {
        // Retrieve data
        $sql_select = "SELECT * FROM user WHERE id_user = '".$_SESSION["id"]."'";
        $stmt = $conn->prepare($sql_select);
        $stmt->execute();
        $row = $stmt->fetch();
    }
    catch(Exception $e) {
        die(var_dump($e));
    }
?>
<html>
    <head>
        <link rel="stylesheet" type="css" href="./css/register.css">
    </head>
    <form action="editAccount.php" method="post" align="center" enctype="multipart/form-data">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                    <fieldset>
                        <div>
                            <img src="<?php echo './uploads/'.$_SESSION["id"].'/profile.jpg'; ?>" alt="Profile Pic" style="width:120px;height 120px;">
                        </div>
                        <br>
                        <div align="center">
                            <input type="file" name="file" id="file">
                        </div>
                        <br><br>
                        <div class="form-group" align="left">
                            First name: <input class="form-control" name="first_name" id="first_name" value="<?php echo ucfirst($row["first_name"]) ?>" type="text" size="30"/>
                        </div>
                        <div class="form-group" align="left">
                            Surname: <input class="form-control" name="surname" id="surname" value="<?php echo ucfirst($row["surname"]) ?>" type="text" size="30"/>
                        </div>
                        <div class="form-group" align="left">
                            Email: <input class="form-control" name="email" id="email" value="<?php echo $row["email"] ?>" type="text" size="30"/>
                        </div>
                        <div class="form-group" align="left">
                            Password: <input class="form-control" name="password" id="password" value="" type="password" size="30"/>
                        </div>
                        <div class="form-group" align="left">
                            Retype: <input class="form-control" name="confirmation" id="confirmation" value="" type="password" size="30"/>
                        </div><br>
                        <div class="form-group" align="left">
                            Gender:<br>
                            <?php if($row["gender"] == "Male"): ?>
                                <input type="radio" name="gender" value="Male" checked> Male
                            <?php else:?>
                                <input type="radio" name="gender" value="Male"> Male
                            <?php endif; ?>

                            <?php if($row["gender"] == "Female"): ?>
                                <input type="radio" name="gender" value="Female" checked> Female
                            <?php else:?>
                                <input type="radio" name="gender" value="Female"> Female
                            <?php endif; ?>

                            <?php if($row["gender"] == "Other"): ?>
                                <input type="radio" name="gender" value="Other" checked> Other
                            <?php else:?>
                                <input type="radio" name="gender" value="Other"> Other
                            <?php endif; ?>

                        </div><br>
                        <div class="form-group" align="left">
                            Birthday: <input type="date" class="form-control" name="birthday" value="<?php echo $row["dob"] ?>" placeholder="YYYY-MM-DD">
                        </div>
                        <div class="form-group" align="left">
                            Privacy Setting:
                            <select name="privacy">
                                <option value=0>Only me</option>
                                <option value=1>Friends</option>
                                <option value=2>Friends of friends</option>
                                <option value=3>Everyone</option>
                            </select>
                        </div><br>
                        <div class="form-group">
                            <button class="btn btn-default" type="submit" style="vertical-align:left; float: center">
                                <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                                Save
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
            // Retrieve data
            $first_name = strtolower($_POST['first_name']);
            $surname = strtolower($_POST['surname']);
            $email = strtolower($_POST['email']);
            $password = $_POST['password'];
            $password_confirm = $_POST['confirmation'];
            $gender = $_POST['gender'];
            $dob = $_POST['birthday'];
            $privacy_setting = $_POST['privacy'];

            if(!test_input($first_name)) {
                echo "<script>alert('You must enter your first name');</script>";
            }
            else if(!test_input($surname)) {
                echo "<script>alert('You must enter your surname');</script>";
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
            else {
                $sql_select = "SELECT * FROM user WHERE id_user != '".$_SESSION["id"]."' AND email = '".$email."'";
                $stmt = $conn->prepare($sql_select);
                $stmt->execute();
                $registrants = $stmt->fetchAll();
                if(count($registrants) >= 1) {
                    echo "<script>alert('Email already registered');</script>";
                }
                else {
                    $sql_update = "UPDATE user SET first_name = '".$first_name."', surname = '".$surname."', email = '".$email."', password = '".sha1($password)."', gender = '".$gender."', dob = '".$dob."', privacy_setting = ".$privacy_setting." WHERE id_user = '".$_SESSION["id"]."'";
                    $stmt = $conn->prepare($sql_update);
                    $stmt->execute();

                    $validextensions = array("jpeg", "jpg", "png");
                    $temporary = explode(".", $_FILES["file"]["name"]);
                    $file_extension = end($temporary);
                    echo $file_extension;

                    if (in_array($file_extension, $validextensions)) {

                        if ($_FILES["file"]["error"] > 0) {
                            echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
                        } else {
                            move_uploaded_file($_FILES["file"]["tmp_name"], "/uploads/".$_SESSION["id"]."/profile.jpg");
                        }   
                    } else {
                        echo "<script>alert('Profile image not updated');</script>";
                    }
                    echo "<script>alert('Account detail updated');</script>";
                }
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
