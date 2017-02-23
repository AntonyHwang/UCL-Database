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
    $row = $stmt->fetchAll();
    if(count($row) == 0) {
        $friendship = "NO";
    }
    else {
        $friendship = "YES";
    }
    //check request sent
    $sql_get_request = "SELECT * FROM friend_request WHERE id_from_user = '".$_SESSION["id"]."' AND id_to_user = '".$_GET['profile']."'";
    $stmt = $conn->prepare($sql_get_request);
    $stmt->execute();
    $row = $stmt->fetchAll();
    if(count($row) == 0) {
        $friend_request = "NO";
    }
    else {
        $friend_request = "SENT";
    }
    
    //check request received
    $sql_get_request = "SELECT * FROM friend_request WHERE id_from_user = '".$_GET['profile']."' AND id_to_user = '".$_SESSION["id"]."'";
    $stmt = $conn->prepare($sql_get_request);
    $stmt->execute();
    $row = $stmt->fetchAll();
    if(count($row) == 0) {
        $friend_request = "NO";
    }
    else {
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
                    <form action="profile.php" method="post" align="center">
                        <button class="btn btn-default" type="submit" style="vertical-align:left; float: center">
                            Send Friend Request
                        </button>
                    </form>

                <?php
                 
                } elseif($friendship == "NO" && $friend_request == "SENT") {
                    echo "Request Sent";
                } elseif($friendship == "NO" && $friend_request == "RECEIVED") {
                    echo "Request Received";
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
                <?php
                    if (isset($_GET['comment']) and $_GET['comment']!=null and isset($_GET['postid'])){
                        $userid = $_SESSION['id'];
                        //echo $_GET['body'];
                        $table = 'post_comment';
                        $body = $_GET['comment'];
                        $postid = $_GET['postid'];
                        $sql = "INSERT INTO ".$table."(id_comment,id_post, id_user,body) VALUES (null, '$postid','$userid','$body')";
                        $stmt = $conn->query($sql);  
                        if (!$stmt){
                        die('post failed');
                        }
                        else {
                            echo"New post created successfully<br>";
                            $_GET['body']=null;
                            unset($_GET['body']);
                        }
                    }
                    $sql = "SELECT id_post, id_user, body FROM post WHERE id_user = ".$_SESSION["id"].' ORDER BY timestamp DESC';
                    $sql2= "SELECT first_name,surname FROM user WHERE id_user = ".$_SESSION["id"].' ';
                    $result = $conn->query($sql);
                    $result2= $conn->query($sql2);
                    while($row2 = $result2->fetch()) {
                        $username= $row2["first_name"]." ".$row2["surname"];
                    }
                    while($row = $result->fetch()) {
                        $postid = $row["id_post"];
                    ?>

                <html> 
                    <div class="panel-body">
                    <h2><?php echo $username; ?>
                    </h2>
                    <paragraph>
                    <?php echo $row["body"];?>
                    </paragraph>
                    <br>
                    <div class="clearfix"></div>
                    <hr>
                </html>
                    <?php
                        //echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
                        $com = "SELECT id_post, id_user,id_comment, body,timestamp FROM post_comment WHERE id_post = ". $row["id_post"].' ORDER BY timestamp DESC';
                        $res_com = $conn->query($com);
                        while($row = $res_com->fetch()){
                        echo "userid/name: " . $row["id_user"]. " " . $row["body"]. " at ".$row["timestamp"]."</br>";
                        }
                        echo "</br>";
                    ?>
                    <form  action = '#' method="get">
                    <div class="input-group">
                    <div class="input-group-btn">
                    <button class="btn btn-default"><i class="glyphicon glyphicon-share"></i></button>
                    </div>
                    <input type="hidden" name="postid" value="<?php echo $postid; ?>" />
                    <input type="text" name = 'comment' class="form-control" placeholder="Add a comment..">
                    </div>
                    </form>
                    </div>
                    <hr>
                    <?php
                    echo "</br></br>";
                    }
                    ?>
                
                


                <br>
                <br>
                </div>
                <div class="col-md-1">
                </div>
            </div>
        </div>
    </body>
</html>