<?php 
    require 'includes/config.php';
    if ($_SESSION["user_type"] == "ADMIN") {
        include_once('adminheader.php');
    }
    else {
        include_once('header.php');
    }
	
	if (isset($_GET['profile']) and $_GET['profile']!=null){
        $sql_select = "SELECT * FROM user WHERE id_user = '".$_GET['profile']."'";
        $stmt = $conn->query($sql_select);
        $row = $stmt->fetch();
        $email = $row["email"];
        $gender = $row["gender"];
        $dob = $row["dob"];
        $privacy_setting = $row["privacy_setting"];
        echo "<title>".ucfirst($row["first_name"])." ".ucfirst($row["surname"])."</title>";
	}
    if ($_SESSION["user_type"] == "ADMIN") {
        $friendship = "YES";
    }
    else {
        //check friendship
        $sql_get = "SELECT * FROM ((SELECT * FROM friendship WHERE id_friend1 = '".$_SESSION["id"]."' OR id_friend2 = '".$_SESSION["id"]."') AS friends) WHERE  id_friend1 = '".$_GET['profile']."' OR id_friend2 = '".$_GET['profile']."'";
        $stmt = $conn->prepare($sql_get);
        $stmt->execute();
        if($stmt->rowCount() == 0) {
            $friendship = "NO";
        }
        else {
            $friendship = "YES";
        }
        //check request sent
        $sql_get_request = "SELECT * FROM friend_request WHERE id_from_user = ".$_SESSION["id"]." AND id_to_user = ".$_GET['profile'];
        $stmt = $conn->prepare($sql_get_request);
        $stmt->execute();
        if($stmt->rowCount() == 0) {
            $friend_request = "NO";
        }
        else {
            $friend_request = "SENT";
        }
        
        //check request received
        $sql_get_request = "SELECT * FROM friend_request WHERE id_from_user = ".$_GET['profile']." AND id_to_user = ".$_SESSION["id"];
        $stmt = $conn->prepare($sql_get_request);
        $stmt->execute();
        if($stmt->rowCount() != 0) {
            $friend_request = "RECEIVED";
        }
    }
?>

<html>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-3">
                    <nav>
                        <ul>
                            <img src="<?php echo './uploads/'.$_GET['profile'].'/profile.jpg'; ?>" alt="Profile Pic" style="width:120px;height 120px;">
                        </ul>
                    </nav>
                </div>
                <div class="col-md-6">
                    <article>
                        <h1><?php echo ucfirst($row["first_name"])." ".ucfirst($row["surname"]);?></h1>
                        <h4>Gender: <?php echo $gender;?></h4>
                        <h4>Email: <?php echo $email;?></h4>
                        <h4>Birthday:  <?php echo $dob;?></h4>
                    </article>
                </div>
                <div class="col-md-1">    
                <?php 
                if ($_SESSION["user_type"] == "ADMIN") {?>
                    <form method="POST" action=''>
                        <div>
                            <input type="submit" class="btn btn-warning" name="delete_account" value="Delete Account" action="#"/>
                        </div>
                        <br>
                        <div>
                            <input type="submit" class="btn btn-primary" name="export_account" value="Export to XML" action="#"/>
                        </div>
                    </form>

                <?php
                }
                else {
                    if($friendship == "NO" && $friend_request == "NO") { 
                ?>
                    <form method="POST" action=''>
                        <input type="submit" class="btn btn-primary" name="send" value="Send Friend Request" />
                    </form>

                    <?php
                    
                    } elseif($friendship == "NO" && $friend_request == "SENT") { ?>
                        <input type="submit" class="btn btn-warning" value="Request Pending" action="#"/>
                    <?php
                    } elseif($friendship == "NO" && $friend_request == "RECEIVED") { ?>
                        <input type="submit" class="btn btn-info" name="status" value="Request Received" action="#"/>
                    <?php
                    } else {?>  
                        <input type="submit" class="btn btn-success" name="status" value="Friend" action="#"/>
                    <?php
                    }
                }?>  
                </div>
                <div class="col-md-1">
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
                      <h1>Posts</h1>
                </div>
                <div class="col-md-1">
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
                </div>
                <div class="col-md-1">
                </div>
            </div>
        </div>
    </body>
</html>

<?php
    if (isset($_POST['send'])) {
        $sql_insert = "INSERT INTO friend_request (id_from_user, id_to_user)VALUES ('".$_SESSION["id"]."','".$_GET['profile']."')";
        echo $sql_insert;
        $stmt = $conn->prepare($sql_insert);
        $stmt->execute();
        header('Location: '.$_SERVER['REQUEST_URI']);
    }
    else if (isset($_POST['delete_account'])) {
        $sql_delete = "DELETE FROM user WHERE id_user = ".$_GET['profile'];
        echo $sql_delete;
        $stmt = $conn->prepare($sql_delete);
        $stmt->execute();
        header('Location: '.$_SERVER['REQUEST_URI']);
    }
    else if (isset($_POST['export_account'])) {
        $sql_export = "SELECT * FROM user WHERE id_user = '".$_GET['profile']."'";
        $stmt = $conn->prepare($sql_export);
        $stmt->execute();
        $row = $stmt->fetch();

        $input = new stdClass;

        $input->id = @trim($row["id_user"]);
        $input->first_name = @trim($row["first_name"]);
        $input->surname = @trim($row["surname"]);
        $input->email = @trim($row["email"]);
        $input->password = @trim($row["password"]);
        $input->gender = @trim($row["gender"]);
        $input->dob = @trim($row["dob"]);
        $input->privacy_setting = @trim($row["privacy_setting"]);

        $doc = new DOMDocument('1.0');
        $doc->formatOutput = true;
        $root = $doc->createElement('user');
        $root = $doc->appendChild($root);
        foreach ($input as $key => $value) {
            $element = $doc->createElement($key, $value);
            $root->appendChild($element);
        }
        $doc->save("./xml_export/".$_GET['profile'].".xml");
        header('location: download.php?profile='.$_GET['profile']);
    }
?>