<?php
    require'includes/config.php';
    include_once('header.php');
    if (isset($_GET['comment']) and $_GET['comment']!=null and isset($_GET['postid'])){
        $userid = $_SESSION['id'];
 echo $userid;
 $table = 'post_comment';
 $body = $_GET['comment'];
 $postid = $_GET['postid'];
 $sql = "INSERT INTO ".$table." (id_comment,id_post, id_user,body) VALUES ( NULL,'$postid','$userid','$body')";
 //$del_com = "delete from ".$table." where id_comment =541";
//echo $sql;
//$dropFK='ALTER TABLE post_comment DROP CONSTRAINT post_comment_ibfk_1';
  //$stmt = $conn->query($dropFK); 
  //$stmt = $conn->query($del_com); 
  $stmt = $conn->query($sql);  
 if (!$stmt){
  die('post failed');
  }
 else {
 echo"New post created successfully<br>";
 $_GET['body']=null;
 unset($_GET['body']);
 header("location:homepage.php");
 }
}
?>


<html>
<style>

.panel-body {
 background-color:white;
}

</style>
</html>




<html>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-3">
                    
                </div>
                <div class="col-md-6">
                    
                </div>
                <div class="col-md-1">       
                </div>
                <div class="col-md-1">
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
<h1>Your Own Posts</h1>
</div>
                <div class="col-md-1">
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
<?php 
if (isset($_GET['id_del']) and $_GET['id_del']!=null ){
	//echo $_GET['body'];
	$table = 'post';

	$post_del = $_GET['id_del'];
	
	$del = "DELETE FROM post WHERE id_post= ".$post_del;
	 $stmt = $conn->query($del);  
	if (!$stmt){
		die('deleting failed');
		}
	else {
	echo " deleted successfully<br>";
	$_GET['id_del']=null;
	unset($_GET['id_del']);
    header("location:homepage.php");
	}
	
}

//above is new part
$sql = "SELECT id_post, id_user, body FROM post WHERE id_user = ".$_SESSION["id"].' ORDER BY timestamp DESC';
$sql2= "SELECT first_name,surname FROM user WHERE id_user = ".$_SESSION["id"].' ';
$result = $conn->query($sql);
$result2= $conn->query($sql2);
while($row2 = $result2->fetch()) {
    $username= ucfirst($row2["first_name"])." ".ucfirst($row2["surname"]);
}
    while($row = $result->fetch()) {
        $postid = $row["id_post"];

        ?>
 
        <div class="panel-body">
        <h2>    
        <?php
        
        echo "<img src= \"./uploads/".$_SESSION["id"]."/profile.jpg\" alt=\"Profile Pic\" style=\"width:50px; height 50px;\">";
        echo "Author: ".$username;
        echo "&nbsp&nbsp&nbsp&nbsp&nbsp";
        echo "<a  href=\"./homepage.php?id_del=".$postid." \"><button class=\"btn btn-success\" >delete</button></a>";
        ?>
        </h2>
        <paragraph>
        <?php
        echo $row["body"];
        ?>
        </paragraph>
        <br>
        <div class="clearfix"></div>
        <hr>
    <?php
        //echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
        $com = "SELECT id_post, id_user,id_comment, body,timestamp FROM post_comment WHERE id_post = ". $row["id_post"].' ORDER BY timestamp DESC';
       
        $res_com = $conn->query($com);
    ?>
    <h3>Comments:</h3>
    <?php    
        while($sqlcomment = $res_com->fetch()){
        $commentUsername = "SELECT first_name,surname FROM user WHERE id_user = ".$sqlcomment["id_user"].' ';
        $res_commentUsername = $conn->query($commentUsername);
        while($sqlcommentUsername = $res_commentUsername->fetch()){
               $commentusername= ucfirst($sqlcommentUsername["first_name"])." ".ucfirst($sqlcommentUsername["surname"]);
        }
        
    
        echo $sqlcomment["body"]. '<strong>'." Posted By: ".'</strong>'.$commentusername.'<strong>'." AT : ".'</strong>'.$sqlcomment["timestamp"]."</br>";
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








<html>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-3">
                    
                </div>
                <div class="col-md-6">
                    
                </div>
                <div class="col-md-1">       
                </div>
                <div class="col-md-1">
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
<h1>Friend's Post </h1>
</div>
                <div class="col-md-1">
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
<?php 
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
        if ($result->rowCount() > 0) {
        // output data of each row  
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {

        // echo $row['first_name'].' '.$row['surname'];
        }
    }
   
    array_push($friends,$friend);       
    }
?>
<?php 
foreach ($friends as $current_id)
{
$sql = "SELECT id_post, id_user, body FROM post WHERE privacy_setting  = '0' AND id_user = ".$current_id.' ORDER BY timestamp DESC';
$result = $conn->query($sql);
$sql2= "SELECT first_name,surname FROM user WHERE id_user = ".$current_id.' ';
$result2= $conn->query($sql2);
while($row2 = $result2->fetch()) {
    $username= ucfirst($row2["first_name"])." ".ucfirst($row2["surname"]);
}
    while($row = $result->fetch()) {
        $postid = $row["id_post"];

        ?>
        <div class="panel-body">
        <h2>    
        <?php
       echo "<img src= \"./uploads/".$current_id."/profile.jpg\" alt=\"Profile Pic\" style=\"width:50px; height 50px;\">";

        echo "Author: ". $username;
        ?>
        </h2>
        <paragraph>
        <?php
        echo $row["body"];
        ?>
        </paragraph>
        <div class="clearfix"></div>
        <hr>
    <?php
        //echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
        $com = "SELECT id_post, id_user,id_comment, body,timestamp FROM post_comment WHERE id_post = ". $row["id_post"].' ORDER BY timestamp DESC';
        $res_com = $conn->query($com);
       
        ?>
         <h3>Comments:</h3>
    <?php    
        while($sqlcomment = $res_com->fetch()){
        $commentUsername = "SELECT first_name,surname FROM user WHERE id_user = ".$sqlcomment["id_user"].' ';
        $res_commentUsername = $conn->query($commentUsername);
        while($sqlcommentUsername = $res_commentUsername->fetch()){
               $commentusername= ucfirst($sqlcommentUsername["first_name"])." ".ucfirst($sqlcommentUsername["surname"]);
        }

        echo $sqlcomment["body"]. '<strong>'." Posted By: ".'</strong>'.$commentusername.'<strong>'." AT : ".'</strong>'.$sqlcomment["timestamp"]."</br>";
        
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
    }

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


 



<html>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-3">
                    
                </div>
                <div class="col-md-6">
                    
                </div>
                <div class="col-md-1">       
                </div>
                <div class="col-md-1">
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
<h1>Friend of Friend's Post </h1>
</div>
                <div class="col-md-1">
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10">
<?php 
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
echo '</br>';
if (in_array($me, $ff)) 
{
    
    unset($ff[array_search($me,$array)]);
}
//remove my friend from  all friends of friends
$remm = array_diff($ff, $friends);

?>

<?php 

foreach ($remm as $current_id)
{
$sql = "SELECT id_post, id_user, body FROM post WHERE privacy_setting  = '2' and  id_user = ".$current_id.'  ORDER BY timestamp DESC';
$result = $conn->query($sql);

$sql2= "SELECT first_name,surname FROM user WHERE id_user = ".$current_id.' ';
$result2= $conn->query($sql2);
while($row2 = $result2->fetch()) {
    $username= ucfirst($row2["first_name"])." ".ucfirst($row2["surname"]);
}
    while($row = $result->fetch()) {



        $postid = $row["id_post"];

        ?>
        <div class="panel-body">
        <h2>    
        <?php
        echo "<img src= \"./uploads/".$current_id."/profile.jpg\" alt=\"Profile Pic\" style=\"width:50px; height 50px;\">";
       
        echo "Author: ". $username;

        ?>
        </h2>
        <paragraph>
        <?php
        echo $row["body"];
        ?>
        </paragraph>
        <div class="clearfix"></div>
        <hr>
    <?php
        //echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
        $com = "SELECT id_post, id_user,id_comment, body,timestamp FROM post_comment WHERE id_post = ". $row["id_post"].' ORDER BY timestamp DESC';
        $res_com = $conn->query($com);
       
        ?>
         <h3>Comments:</h3>
    <?php    
        while($sqlcomment = $res_com->fetch()){
        $commentUsername = "SELECT first_name,surname FROM user WHERE id_user = ".$sqlcomment["id_user"].' ';
        $res_commentUsername = $conn->query($commentUsername);
        while($sqlcommentUsername = $res_commentUsername->fetch()){
               $commentusername= ucfirst($sqlcommentUsername["first_name"])." ".ucfirst($sqlcommentUsername["surname"]);
        }

        echo $sqlcomment["body"]. '<strong>'." Posted By: ".'</strong>'.$commentusername.'<strong>'." AT : ".'</strong>'.$sqlcomment["timestamp"]."</br>";
        
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
    }

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



<h1>Circle's Post </h1>
<br>