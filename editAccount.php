<?php 
    require 'includes/config.php'; 
    include_once('header.php');

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
    <form action="editAccount.php" method="post" align="center">
        <fieldset>
            <div>
                <img src="<?php echo './uploads/'.$_SESSION["id"].'/profile.jpg'; ?>" alt="Profile Pic" style="width:120px;height 120px;">
            </div>
            <br>
            <form action="editAccount.php" method="post" enctype="multipart/form-data">
                <input type="file" name="fileToUpload" id="fileToUpload">
            </form>
            <br><br>
            <div class="form-group" align="left">
                First name: <input autofocus class="form-control" name="first_name" id="first_name" value="<?php echo ucfirst($row["first_name"]) ?>" type="text" size="30"/>
            </div>
            <div class="form-group" align="left">
                Surname: <input class="form-control" name="surname" id="surname" value="<?php echo ucfirst($row["surname"]) ?>" type="text" size="30"/>
            </div>
            <div class="form-group" align="left">
                Email: <input class="form-control" name="email" id="email" value="<?php echo $row["email"] ?>" type="text" size="30"/>
            </div>
            <div class="form-group" align="left">
                Password: <input class="form-control" name="password" id="password" value="<?php echo $row["password"] ?>" type="password" size="30"/>
            </div>
            <div class="form-group" align="left">
                Retype: <input class="form-control" name="confirmation" id="confirmation" value="<?php echo $row["password"] ?>" type="password" size="30"/>
            </div><br>
            <div class="form-group" align="left">
                Gender:<br>
                <?php if($row["gender"] == "male"): ?>
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
            </div><br>
            <div class="form-group">
                <button class="btn btn-default" type="submit" style="vertical-align:left; float: center">
                    <span aria-hidden="true" class="glyphicon glyphicon-log-in"></span>
                    Save
                </button>
            </div><br>
        </fieldset>
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
            $gender = $_POST['gender'];
            $dob = $_POST['birthday'];
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
            else {
                $sql_select = "SELECT * FROM user WHERE id_user != '".$_SESSION["id"]."' AND email = '".$email."'";
                $stmt = $conn->prepare($sql_select);
                $stmt->execute();
                $registrants = $stmt->fetchAll();
                if(count($registrants) >= 1) {
                    echo "<h2>Email already registered</h2>";
                }
                else {
                    $sql_update = "UPDATE user SET first_name = '".$first_name."', surname = '".$surname."', email = '".$email."', password = '".$password."', gender = '".$gender."', dob = '".$dob."' WHERE id_user = '".$_SESSION["id"]."'";
                    $stmt = $conn->prepare($sql_update);
                    $stmt->execute();

                    //need to be fixed
                    $target_dir = "uploads/".$_SESSION["id"]."/";
                    $target_file = $target_dir . $_SESSION["email"]. basename($_FILES["fileToUpload"]["name"]);
                    $uploadOk = 1;
                    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                    // Check if image file is a actual image or fake image
                    if(isset($_POST["submit"])) {
                        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                        if($check !== false) {
                            echo "File is an image - " . $check["mime"] . ".";
                            $uploadOk = 1;
                        } else {
                            echo "File is not an image.";
                            $uploadOk = 0;
                        }
                    }
                    // Check file size
                    if ($_FILES["fileToUpload"]["size"] > 1000000) {
                        echo "Sorry, your file is too large.";
                        $uploadOk = 0;
                    }
                    // Allow certain file formats
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                        echo "Sorry, only JPG, JPEG & PNG files are allowed.";
                        $uploadOk = 0;
                    }
                    // Check if $uploadOk is set to 0 by an error
                    if ($uploadOk == 0) {
                        echo "Sorry, your file was not uploaded.";
                    // if everything is ok, try to upload file
                    } else {
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
                        } else {
                            echo "Sorry, there was an error uploading your file.";
                        }
                    }
                    echo "<h2>Account detail updated</h2>";
                }
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
