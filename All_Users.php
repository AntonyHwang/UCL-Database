<?php 
    require 'includes/config.php'; 
    include_once('adminheader.php');
?>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-1">
            </div>
            <div class="col-md-2">
                <form action="All_Users.php" method="post" enctype="multipart/form-data">
                    Select XML to Import: 
                    <br>
                    <input type="file" name="file" id="file">
            </div>
            <div class="col-md-9">
                    <input type="submit" value="Import" class="btn btn-primary" id="import" name="import">
                </form>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-md-1">
            </div>
            <div class="col-md-10">
                <?php 
                    $sql= "SELECT id_user, first_name,surname FROM user ";
                    $result = $conn->query($sql);
                    $count = 1;

                    while($row = $result->fetch()) {
                        $count++;
                        $username= ucfirst($row["first_name"])." ".ucfirst($row["surname"]);
                        $user_id = $row["id_user"];
                        $profile_img = "./uploads/".$user_id."/profile.jpg";
                        $profile_link = "./profile.php?profile=".$row[id_user];
                        if ($count % 2 != 0) {
                ?>
                        <div class="row">
                        <?php
                        }
                        ?>
                            <div class="col-md-6">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <img src="<?php echo $profile_img ?>" class="img-rounded" style="width:50px; height 50px;" />
                                        <?php echo "   ".$username ?>
                                    </div>
                                    <div class="col-md-8">  
                                        <a href="./myProfilePage.php?profile=<?php echo $row[id_user]?>" class="btn btn-success">Profile Page</button></a>
                                    </div>
                                </div>
                                <div class="col-md-12">
        
                                </div>  
                            </div>
                        <?php
                        if ($count % 2 != 0) {
                        ?>
                        </div>
                        <br>
                        <br>
                        <?php
                        }
                        ?>
                    <?php
                    }
                    ?>
            </div>
            <div class="col-md-1">
            </div>
        </div>
    </div>
</body>

<?php
    if (isset($_POST['import'])) {
        $validextensions = array("xml");
        $temporary = explode(".", $_FILES["file"]["name"]);
        $file_extension = end($temporary);

        if (in_array($file_extension, $validextensions)) {

            if ($_FILES["file"]["error"] > 0) {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br/><br/>";
            } else {
                echo "<span>Your File Uploaded Succesfully...!!</span><br/>";
                move_uploaded_file($_FILES["file"]["tmp_name"], "./xml_import/".$_SESSION["id"].".xml");
                $doc = new DOMDocument();
                $doc=simplexml_load_file("./xml_import/".$_SESSION["id"].".xml");
                $id = $doc->id;
                $fname = $doc->first_name;
                $surname = $doc->surname;
                $email = $doc->email;
                $password = $doc->password;
                $gender = $doc->gender;
                $dob = $doc->dob;
                $privacy_setting = $doc->privacy_setting;

                $sql_select = "SELECT * FROM user WHERE email = '".$email."'";
                $stmt = $conn->query($sql_select);
                $registrants = $stmt->fetchAll();
                if(!test_input($fname)) {
                    echo "<script>alert('XML profile info incomplete');</script>";
                }
                else if(!test_input($surname)) {
                    echo "<script>alert('XML profile info incomplete');</script>";
                }
                else if(!test_input($email)) {
                    echo "<script>alert('XML profile info incomplete');</script>";
                }
                else if(!test_input($password)) {
                    echo "<script>alert('XML profile info incomplete');</script>";
                }
                else if(!test_input($gender)) {
                    echo "<script>alert('XML profile info incomplete');</script>";
                }
                else if(!test_input($dob)) {
                    echo "<script>alert('XML profile info incomplete');</script>";
                }
                else if(count($registrants) != 0) {
                    echo "<h2>Account already registered</h2>";
                } else {
                    $sql_insert = "INSERT INTO user (first_name, surname, email, password, gender, dob, privacy_setting)VALUES ('".$fname."','".$surname."','".$email."','".$password."','".$gender."','".$dob."', 0);";
                    $sql_get_id = "SELECT id_user FROM user WHERE email = '".$email."';";
                    $stmt = $conn->prepare($sql_insert);
                    $stmt->execute();;
                    $stmt = $conn->prepare($sql_get_id);
                    $stmt->execute();
                    $rows = $stmt->fetch();
                    mkdir("./uploads/".$rows["id_user"]);
                    $default_profile_pic = './uploads/default-profile.jpg';
                    $user_profile_pic = './uploads/'.$rows["id_user"].'/profile.jpg';
                    copy($default_profile_pic, $user_profile_pic);
                    echo "<script>alert('Profile imported');</script>";
                }
                unlink('xml_import/'.$_SESSION["id"].'.xml');
            }   
        } else {
            echo "<script>alert('Profile XML not imported');</script>";
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