<?php

    require'includes/config.php';
    include_once('header.php');
    include_once('circleTool.php');

   $allMembers=allCircleMember($_SESSION["id"],$conn);
   $CirclePosts = posts($allMembers,$conn);
   $CirclePhotos = photos($allMembers , $conn);
   //print_r($allMembers);
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
?>



<style>
.panel-body {
background-color:white;
}
.posts {
    
    margin: auto;
    
}
</style>

<?php 
    $allposts=[];  
    $sql = "SELECT id_post, id_user, body FROM post WHERE id_user = ".$_SESSION["id"].' ORDER BY timestamp DESC';
    $result = $conn->query($sql);
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
        }else if($value[1]==$thisid){
            $friend = $value[0];
        }else{
            $friend = $value[1];
        }
        array_push($friends,$friend);       
    }
 //for each my friend ,get their posts
foreach ($friends as $current_id){
    $sql = "SELECT id_post, id_user, body FROM post WHERE (privacy_setting  = '0' or   privacy_setting  = '2' ) AND id_user = ".$current_id.' ORDER BY timestamp DESC';
    $result = $conn->query($sql);
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
    if ($result->rowCount() > 0) {
        $fri2 = $result->fetchAll();
        //add their all friends
        foreach ($fri2 as $row) {
            if($row[0]==$member)
            array_push($ff,$row[1]);
            else array_push($ff,$row[0]);
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
foreach ($remm as $current_id){
    $ffsql = "SELECT id_post, id_user, body FROM post WHERE privacy_setting  = '2' and  id_user = ".$current_id.'  ORDER BY timestamp DESC';
    $result = $conn->query($ffsql);
    while($row = $result->fetch()) {
        $postid = $row["id_post"];
        array_push($allposts,$postid);
    }
}
$myposts = [];
$me = $_SESSION["id"];
$myposts_sql = "SELECT id_post, id_user, body FROM post WHERE id_user = '".$me."'  ORDER BY timestamp DESC";
$MyPostlist = $conn->query($myposts_sql);
while($row = $MyPostlist->fetch()) {
    $postid = $row["id_post"];
    array_push($myposts,$postid);
}

$usersseen=array_merge($remm,$friends);
sort($allposts);
$my_other_posts=array_merge($allposts,$myposts);
$my_other_posts=array_merge($my_other_posts,$CirclePosts);

$allposts = $my_other_posts;
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
                    <h1>Posts | <a href="homepage_photo.php">Photos</a></h1>    
                    <hr>
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
                    <div class = 'posts'>
                        <div class="well"> 
                            <form class="form-horizontal" role="form" action="server.php" method="get">
                                <h4>What's New</h4>
                                <div class="form-group" style="padding:14px;">
                                    <textarea class="form-control" placeholder="Update your status" name='body'></textarea>
                                    </br>Privacy: </br>
                                    <input class = "checkbox-inline" type="radio" name='privacy' value="0">Friend
                                    <input class = "checkbox-inline" type="radio" name='privacy' value="1">Circles
                                    <input class = "checkbox-inline" type="radio" name='privacy' value="2">Friends of friends     
                                
                                </div>


                                <button class="btn btn-primary pull-right" type="submit">Post</button><ul class="list-inline"><li><a href="photoPage.php?id=<?php echo $_SESSION['id']?>"><i class="glyphicon glyphicon-camera"></i></a>  Upload a New Photo</li></ul>
                            </form>
                        </div>
                    </div>                        
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
        echo "<img src= \"./uploads/".$postOwner."/profile.jpg\" class=\"img-rounded\" alt=\"Profile Pic\" style=\"width:50px; height 50px;\">";
        echo "&nbsp <a href=\"./profile.php?profile=".$postOwner."\" >$username</a>\n";   
        echo "&nbsp&nbsp&nbsp&nbsp&nbsp";
        ?>
    </h2>
    <paragraph>
                        <?php 
                        echo "<h3>&nbsp&nbsp&nbsp&nbsp".$row["body"]."</h3>";
                        echo "<br>";
                        echo '<small style=\" float = right\">'.$row['timestamp'] .'</small>';
                        
                        ?>
    </paragraph>
    </br>
    <hr>
    <?php
    $com = "SELECT id_post, id_user,id_comment, body,timestamp FROM post_comment WHERE id_post = ". $row["id_post"].' ORDER BY timestamp';
    $res_com = $conn->query($com);
    ?>
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
echo "          <img src= \"./uploads/".$sqlcomment["id_user"]."/profile.jpg\" class=\"img-rounded\" alt=\"Profile Pic\" style=\"width:40px; height 40px;\">";
echo "		</div>\n"; 
echo "		<div class=\"col-md-10\">\n"; 
echo "			<div class=\"row\">\n"; 
echo "				<div class=\"col-md-12\">\n"; 
//echo "<a href=\"./profile.php?profile=".$postOwner."\" >$username</a>\n";  
    
echo "<strong><a href=\"./profile.php?profile=".$sqlcomment["id_user"]."\" >".$commentusername." </a>: </strong><h4>&nbsp&nbsp&nbsp&nbsp".$sqlcomment["body"]."</h4>";

?>



<?php
//echo "                example user: this is a  test comment\n"; 
echo "				</div>\n"; 
echo "			</div>\n"; 
echo "			<div class=\"row\">\n"; 
echo "          <br>";
echo "				<div class=\"col-md-12\">\n"; 
//echo "                at 2010 5 7\n"; 
echo "                <small style=\" float = right\">".$sqlcomment['timestamp'] ."</small>";;
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






