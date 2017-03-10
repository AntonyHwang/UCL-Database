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
        //echo $sql_get_request;
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
    //check the privacy setting 
   function getFriends($user,$conn){
		$friend_list =[];
		$getlist = "SELECT * FROM `friendship` WHERE `id_friend1` =".$user. " OR `id_friend2` =".$user;
		$result = $conn->query($getlist);
		$data = $result->fetchAll();		
		foreach ($data as $value){
			if($value[1]==$user){
				$friend = $value[0];
			}else{
				$friend = $value[1];
			}
			array_push($friend_list,$friend);
		}
		return $friend_list;
	}
    $myfriends = getFriends($_SESSION["id"],$conn);

    $isAdmin=false;
    $isff=false;
    $isfriend=false;
    $isfriend=in_array($_GET["profile"], $myfriends);

	function getFriendsFriends($friend_list,$conn){
		$all = [];
		foreach($friend_list as $one_friend){
			$onelist = getFriends($one_friend,$conn);
			$all = array_merge($onelist, $all);
		}
		return $all;
	}    
    $myff = getFriendsFriends($myfriends,$conn);
    $isff=in_array($_GET["profile"], $myff);
   // echo "friend :".$isfriend."| ff: ".$isff;
    //echo gettype($isff);
    $privacy=false;
    if($_SESSION["user_type"] == "ADMIN")$privacy=true;
    else if($privacy_setting=="0"){
        $privacy = false;
    }else if(($privacy_setting=="1")&& $isfriend==true){
        $privacy = true;
    }else if($privacy_setting=="2" && $isff==true){
        echo "p2";
        $privacy = true;
    }else if($privacy_setting=="2" && $isfriend==true){
        echo "p2";
        $privacy = true;
    }
    else if($privacy_setting=="3"){
        $privacy = true;
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
                <?php
                //echo gettype($privacy_setting);
                //echo $privacy_setting;
                 if($privacy==true){
                ?>
                    <article>
                        <h1><?php echo ucfirst($row["first_name"])." ".ucfirst($row["surname"]);?></h1>
                        <h4>Gender: <?php echo $gender;?></h4>
                        <h4>Email: <?php echo $email;?></h4>
                        <h4>Birthday:  <?php echo $dob;?></h4>
                    </article>
                <?php
                } 
                ?>
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
                      <h1>Posts <a href="album.php?profile=<?php echo $_GET["profile"];?>">Photos</a></h1>
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

<?php 

    $allposts=[];
    //above is new part
    $sql = "SELECT id_post, id_user, body FROM post WHERE id_user = ".$_SESSION["id"].' ORDER BY timestamp DESC';
    $result = $conn->query($sql);
    $friends=array();
    $thisid = $_SESSION["id"];
    $sql = "SELECT * FROM `friendship` WHERE `id_friend1` =".$thisid. " OR `id_friend2` =".$thisid;
    $result = $conn->query($sql);
    $list = [];
    //get all friends
    $array = $result->fetchAll();
    $friends = $myff;

 //for each my friend ,get their posts
foreach ($friends as $current_id)
{
    $sql = "SELECT id_post, id_user, body FROM post WHERE privacy_setting  = '0' AND id_user = ".$current_id.' ORDER BY timestamp DESC';
    $result = $conn->query($sql);
    while($row = $result->fetch()) {
    $postid = $row["id_post"];
        array_push($allposts,$postid);
    }

}


$ff = [];
//each my friend ,member,member f 's friends 
$ff = getFriendsFriends($friends,$conn);
$ff=array_unique($ff);
//remove depulicate
$me =$_SESSION['id'];
//remove this user from the list

if (in_array($me, $ff))     unset($ff[array_search($me,$ff)]);

//remove my friend from  all friends of friends
$remm = array_diff($ff, $friends);

//for each user in the friend of friend ,get his posts
foreach ($remm as $current_id)
{
    $ffsql = "SELECT id_post, id_user, body FROM post WHERE privacy_setting  = '2' and  id_user = ".$current_id.'  ORDER BY timestamp DESC';
    $result = $conn->query($ffsql);
    while($row = $result->fetch()) {
        $postid = $row["id_post"];
        array_push($allposts,$postid);
    }

}
$usersseen=array_merge($remm,$friends);
sort($allposts);
$profile_id = $_GET['profile'];
$postlist_oneuser=[];
$posts_user = "SELECT id_post, id_user, body FROM post WHERE  id_user = ".$profile_id.'  ORDER BY timestamp DESC';
$result = $conn->query($posts_user);
foreach($result as $user){
    array_push($postlist_oneuser,$user[0]);
}
$post_user_allow_seen = array_intersect($postlist_oneuser,$allposts);

//$sortedpostlist = sortPostbytime($conn,$allposts);
?>


<html>
    <body>
        <div class="container-fluid">
             <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">       


<!-- the post users-->

<?php 


$allposts =array_reverse($post_user_allow_seen);
foreach($allposts as $current_postid){
$getpost = "SELECT id_post, id_user, body,timestamp FROM post WHERE id_post = ".$current_postid;
$getpostresult = $conn->query($getpost);

while($row = $getpostresult->fetch()) {
$postid = $row["id_post"];
$postOwner = $row["id_user"];
$getpostowner = "SELECT first_name,surname FROM user WHERE id_user = ".$postOwner.' ';
$getpostowernresult = $conn->query($getpostowner);
$namerow = $getpostowernresult->fetch();
$username= ucfirst($namerow["first_name"])." ".ucfirst($namerow["surname"]);

?>

<div class="panel-body">
    <h2 class ="post_owner">    
        <?php

        echo "<img src= \"./uploads/".$postOwner."/profile.jpg\" alt=\"Profile Pic\" style=\"width:50px; height 50px;\">";
//echo "<a href=\"./profile.php?profile=".$postOwner."\"</a>";
        echo "<a href=\"./profile.php?profile=".$postOwner."\" >$username</a>\n";  
    
        //echo "".$username;
        echo "&nbsp&nbsp&nbsp&nbsp&nbsp";
        //echo "<a  href=\"./homepage.php?id_del=".$postid." \"><button class=\"btn btn-success\" >delete</button></a>";
        ?>
    </h2>
    <paragraph>
        <?php
        echo '<strong style=\" float = right\">'.$row['timestamp'] .'</strong>';

        echo '</br>';
        echo '</br>';
        echo $row["body"];


        echo '</br>';
        //echo '</br></br> at '.$row['timestamp'];
        ?>
    </paragraph>
    </br>
    <hr>
    <?php
    //echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
    $com = "SELECT id_post, id_user,id_comment, body,timestamp FROM post_comment WHERE id_post = ". $row["id_post"].' ORDER BY timestamp DESC';

    $res_com = $conn->query($com);
    ?>
<!--   <div class="row">
		<div class="col-md-1">
			<img alt="Bootstrap Image Preview" src="http://lorempixel.com/140/140/" width = "40px"/>
		</div>
		<div class="col-md-11">
			<div class="row">
				<div class="col-md-12">
                example user: this is a  test comment
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
                at 2010 5 7
				</div>
			</div>
		</div>
	</div> -->
    <?php 

  
    while($sqlcomment = $res_com->fetch()){
        $commentUsername = "SELECT first_name,surname FROM user WHERE id_user = ".$sqlcomment["id_user"].' ';
        $res_commentUsername = $conn->query($commentUsername);
        while($sqlcommentUsername = $res_commentUsername->fetch()){
        $commentusername= ucfirst($sqlcommentUsername["first_name"])." ".ucfirst($sqlcommentUsername["surname"]);
        }
//picture and two rows goes here
echo "   <div class=\"row\">\n"; 
echo "		<div class=\"col-md-1\">\n"; 
echo "          <img src= \"./uploads/".$sqlcomment["id_user"]."/profile.jpg\" alt=\"Profile Pic\" style=\"width:40px; height 40px;\">";
echo "		</div>\n"; 


echo "		<div class=\"col-md-10\">\n"; 
echo "			<div class=\"row\">\n"; 
echo "				<div class=\"col-md-12\">\n"; 
//echo "<a href=\"./profile.php?profile=".$postOwner."\" >$username</a>\n";  
    
echo "<a href=\"./profile.php?profile=".$sqlcomment["id_user"]."\" >".$commentusername." </a>:".$sqlcomment["body"];

?>



<?php
//echo "                example user: this is a  test comment\n"; 
echo "				</div>\n"; 
echo "			</div>\n"; 
echo "			<div class=\"row\">\n"; 
echo "				<div class=\"col-md-12\">\n"; 
//echo "                at 2010 5 7\n"; 
echo "                ".$sqlcomment["timestamp"];
echo "				</div>\n"; 
echo "			</div>\n"; 

echo "		</div>\n"; 
//button might go here
echo "		<div class=\"col-md-1\">\n"; 
if($sqlcomment["id_user"]==$_SESSION['id']){
?>



        <form  action = 'server.php' method="get">
            <div class="input-group">
                <div class="input-group-btn">
                    <button type="submit" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-remove"></span>  
                    </button>
                </div>
                <input type="hidden" name="profile" value="<?php echo $_GET["profile"]; ?>" /> 
                <input type="hidden" name="last_page" value="profile.php" />               
           
                <input type="hidden" name="id_del_comment" value="<?php echo $sqlcomment["id_comment"]; ?>" />                
            </div>
        </form>



<?php
}
echo "		</div>\n"; 

echo "	</div>\n";
echo "</br>";
//button end
        
    }
    echo "</br>";
    ?>
    <!--post a comment-->
        <form  action = 'server.php' method="get">
            <div class="input-group">
                <div class="input-group-btn">
                    <button class="btn btn-default"><i class="glyphicon glyphicon-share"></i></button>
                </div>
                <input type="hidden" name="profile" value="<?php echo $_GET["profile"]; ?>" />  
                <input type="hidden" name="last_page" value="profile.php" />                 
           
                <input type="hidden" name="postid" value="<?php echo $postid; ?>" />
                <input type="text" name = 'comment' class="form-control" placeholder="Add a comment..">
            </div>
        </form>
    <!--end _post a comment-->    
    </div>
    <!--end of one post-->
<hr>
<?php

}
}
?>
                </div>
                <div class="col-md-1">
                </div>
            </div>
        </div>
    </body>
</html>






