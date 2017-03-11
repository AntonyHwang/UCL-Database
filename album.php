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
                      <h1><a href="profile.php?profile=<?php echo $_GET["profile"];?>">Posts</a> | Photos</h1>                
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
//pho



    $friends=array();
    $thisid = $_SESSION["id"];
    $sql = "SELECT * FROM `friendship` WHERE `id_friend1` =".$thisid. " OR `id_friend2` =".$thisid;
    $result = $conn->query($sql);
    $list = [];
    //get all friends
    $array = $result->fetchAll();
    foreach ($array as $value) {
        if($value[1]==$value[0]){
            countinue;
        }
        if($value[1]==$thisid){
            $friend = $value[0];
        }
        else{
            $friend = $value[1];
        }
        $sql = "SELECT * FROM `user` WHERE `id_user` =".$value[1];
        $result = $conn->query($sql);   
        array_push($friends,$friend);       
    }
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
 //for each my friend ,get their photo
 $allphotos = [];
foreach ($friends as $current_id)
{
    $sql = "SELECT id_photo, id_user FROM photo WHERE privacy_setting  = '0' AND id_user = ".$current_id.' ORDER BY timestamp DESC';
    $result = $conn->query($sql);
    while($row = $result->fetch()) {
        $postid = $row["id_photo"];
        array_push($allphotos,$postid);
    }
} 

$ff = [];
//each my friend ,member,member f 's friends 
foreach ($friends as $member) {     
    $sql = "SELECT * FROM `friendship` WHERE `id_friend1` =".$member.' or id_friend2='.$member ;
    $result = $conn->query($sql);
    //echo 'user '.$member.' got '.$result->num_rows.'  friends</br>';
    if ($result->rowCount() > 0) {
        $fri2 = $result->fetchAll();
        //add their all friends
        foreach ($fri2 as $row) {
            //echo 'adding '.$row[1].'</br>';
            if($row[0]==$member)
            array_push($ff,$row[1]);
            else array_push($ff,$row[0]);
            //ff is friends s friends
        }
    }
}
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
/*    $sql2= "SELECT first_name,surname FROM user WHERE id_user = ".$current_id.' ';
    $result2= $conn->query($sql2);
    while($row2 = $result2->fetch()) {
        $username= ucfirst($row2["first_name"])." ".ucfirst($row2["surname"]);
    }*/
    while($row = $result->fetch()) {
        $postid = $row["id_post"];
        array_push($allposts,$postid);
    }
}
//photo
foreach ($remm as $current_id)
{
    $ffsql = "SELECT id_photo, id_user FROM photo WHERE privacy_setting  = '2' and  id_user = ".$current_id.'  ORDER BY timestamp DESC';
    $result = $conn->query($ffsql);
    while($row = $result->fetch()) {
        $postid = $row["id_photo"];
        array_push($allphotos,$postid);
    }
}
$usersseen=array_merge($remm,$friends);
sort($allphotos);
//get posts of this user and do intersection with all allow_to_seen_posts
$profile_id = $_GET['profile'];

//get photo of this user and do intersection with all allow_to_seen_photo
$photolist_oneuser=[];
$photos_user = "SELECT id_photo, id_user FROM photo WHERE  id_user = ".$profile_id.'  ORDER BY timestamp DESC';
$result = $conn->query($photos_user);
foreach($result as $user){
    array_push($photolist_oneuser,$user[0]);
}
$photo_user_allow_seen = array_intersect($photolist_oneuser,$allphotos);
//$sortedpostlist = sortPostbytime($conn,$allposts);
?>





<?php 
$number_photos =count($photo_user_allow_seen);

$remain = $number_photos % 3;
echo $remain."  ";
$index = 0;
$row=0;
if($remain == 0)$row = floor($number_photos/3);
else $row=floor($number_photos/3) +1;
echo $row;


               

?>
<div class="container-fluid">
	<div class="row">
        <div class="col-md-1">
        </div>
		<div class="col-md-10">
        
            <?php
            foreach($photo_user_allow_seen as $photo_id){
            if($index % 3 ==0) echo "<div class=\"row\">";
                $current_photo = $photo_user_allow_seen[$index];
                $sql_select = ("SELECT * FROM photo WHERE id_photo = '".$current_photo."' ORDER BY id_photo DESC");
                $stmt = $conn->query($sql_select);
                $row = $stmt->fetch();
                $index++;
            ?>

			
				<div class="col-md-4">
					<div class="thumbnail">
						<img class="center-block" style="max-width:100%;max-height:300px;"src="<?php echo $row["file_path"]?>">
                        <div class="caption">
							<h3>
								<?php echo $row["body"]?>
							</h3>

							<p>
                                <?php
                                $photoViewLink = "photoViewer.php?id=".$row["id_user"]."&photoPath=".$row["file_path"]."&caption=".$row["body"]."&photo_id=".$row['id_photo']."&user=".$_SESSION["id"];
                                ?>
                            
								<a class="btn btn-primary" href="<?php echo $photoViewLink;?>">comment</a> 
							</p>
						</div>
					</div>
				</div>				
            <?php 
            if($index % 3 ==0)echo "</div>";
            }
            ?>
		</div>
        <div class="col-md-1">
        </div>
	</div>
</div>
