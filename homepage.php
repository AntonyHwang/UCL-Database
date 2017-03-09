<?php

    require'includes/config.php';
    include_once('header.php');
    function sortPostbytime($connect,$posts) {
        $newlist = [];
        foreach($posts as $postid){
            $date = post2date($connect,$postid);
            $t = explode(" ",$date);
            $ymd = $t[0];
            $newlist[$ymd] = $postid;                    
        }       
        $newlist = ksort($newlist);
        return $newlist;       
    }
    function post2date($connect,$post){
        
        $sqlgetDate = "SELECT id_post,timestamp FROM post WHERE id_post = ".$post;
        //$sqlgetDate = "select * from post where id_post = ".$post;
        $res = $connect->query($sqlgetDate);
        $row= $res->fetch();
        return $row['timestamp'];
    }





    //delete comments
    
?>



<style>

.panel-body {
background-color:#F0F8FF;
}
.right{
    
    text-align: right;

}

</style>





<?php 

    $allposts=[];
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

        $com = "SELECT id_post, id_user,id_comment, body,timestamp FROM post_comment WHERE id_post = ". $row["id_post"].' ORDER BY timestamp DESC';

        $res_com = $conn->query($com);

    }

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
 //for each my friend ,get their posts
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
        array_push($allposts,$postid);
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

    $sql2= "SELECT first_name,surname FROM user WHERE id_user = ".$current_id.' ';
    $result2= $conn->query($sql2);
    while($row2 = $result2->fetch()) {
        $username= ucfirst($row2["first_name"])." ".ucfirst($row2["surname"]);
    }
    while($row = $result->fetch()) {
        $postid = $row["id_post"];
        array_push($allposts,$postid);
    }

}
$usersseen=array_merge($remm,$friends);
sort($allposts);
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


$allposts =array_reverse($allposts);
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
                
                    <input type="hidden" name="last_page" value="homepage.php" /> 
                    <button type="submit" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-remove"></span>  
                    </button>
                </div>
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
    <!--add a comment-->
        <form  action = 'server.php' method="get">
            <div class="input-group">
                <div class="input-group-btn">
                    <button class="btn btn-default"><i class="glyphicon glyphicon-share"></i></button>
                </div>
                <input type="hidden" name="last_page" value="homepage.php" /> 
                <input type="hidden" name="postid" value="<?php echo $postid; ?>" />
                <input type="text" name = 'comment' class="form-control" placeholder="Add a comment..">
            </div>
        </form>
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






