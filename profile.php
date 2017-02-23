<?php 
    require 'includes/config.php'; 
    include_once('header.php');
	
    $sql_select = "SELECT * FROM user WHERE id_user = '".$_SESSION["id"]."'";
    $stmt = $conn->query($sql_select);
    $row = $stmt->fetch();
    $email = $row["email"];
    $gender = $row["gender"];
    $dob = $row["dob"];
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
                if($friendship == "NO" && $friend_request == "NO") { 
                ?>

                <form method="POST" action=''>
                    <input type="submit" class="button" name="send" value="Send Friend Request" />
                </form>

                <?php
                 
                } elseif($friendship == "NO" && $friend_request == "SENT") { ?>
                    <input type="submit" class="button" name="status" value="Request Pending" action="#"/>
                <?php
                } elseif($friendship == "NO" && $friend_request == "RECEIVED") { ?>
                    <input type="submit" class="button" name="status" value="Request Received" action="#"/>
                <?php
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
?>