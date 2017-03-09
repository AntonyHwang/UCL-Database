<?php 
    require 'includes/config.php';
    if ($_SESSION["user_type"] == "ADMIN") {
        include_once('adminheader.php');
    }
    else {
        include_once('header.php');
    }
     


?>


<?php

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
if (in_array($me, $ff))     unset($ff[array_search($me,$array)]);
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
//get photo of this user and do intersection with all allow_to_seen_photo
$photo_user_allow_seen =$allphotos;

//$sortedpostlist = sortPostbytime($conn,$allposts);
?>





<?php 
$number_photos =count($photo_user_allow_seen);

$remain = $number_photos % 3;

$index = 0;
$row=0;
if($remain == 0)$row = floor($number_photos/3);
else $row=floor($number_photos/3) +1;


               

?>
<div class="container-fluid">
	<div class="row">
        <div class="col-md-1">
        </div>
		<div class="col-md-10">
            <h1>Photos <a href="homepage.php">Posts</a></h1>    
		</div>
        <div class="col-md-1">
        </div>
	</div>
</div>

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