<?php
require 'includes/config.php'; 
include_once('header.php');
    
?>
<!DOCTYPE html>
<html>
<style>
.posts {
    width: 500px;
    margin: auto;
    
}
.wrapper{
    background-color:
}
#grad {
  background: blue; /* For browsers that do not support gradients */
  background: -webkit-linear-gradient(left top, red, yellow); /* For Safari 5.1 to 6.0 */
  background: -o-linear-gradient(bottom right, red, yellow); /* For Opera 11.1 to 12.0 */
  background: -moz-linear-gradient(bottom right, red, yellow); /* For Firefox 3.6 to 15 */
  background: linear-gradient(to bottom right, blue, yellow); /* Standard syntax */
}
.panel-body {
    background-color:white;
}
</style>
<body>
<div class = 'posts'>
<div class="well"> 
   <form class="form-horizontal" role="form" action="#" method="get">
    <h4>What's New</h4>
     <div class="form-group" style="padding:14px;">
      <textarea class="form-control" placeholder="Update your status" name='body'></textarea>
    </br>Privacy: </br>
<input class = "checkbox-inline" type="radio" name='privacy' value="0">friend
<input class = "checkbox-inline" type="radio" name='privacy' value="1">circles
<input class = "checkbox-inline" type="radio" name='privacy' value="2">friends of friends     
      
    </div>


    <button class="btn btn-primary pull-right" type="submit">Post</button><ul class="list-inline"><li><a href=""><i class="glyphicon glyphicon-upload"></i></a></li><li><a href=""><i class="glyphicon glyphicon-camera"></i></a></li><li><a href=""><i class="glyphicon glyphicon-map-marker"></i></a></li></ul>
  </form>
</div>


<?php
// Create connection
            try {
                $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
                $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
          
            }
            catch(Exception $e){
                die(var_dump($e));
            }
            
 
//$_SESSION["id"]=91;
$userid = $_SESSION["id"];
//handle post 
if (isset($_GET['body']) and $_GET['body']!=null){
    //echo $_GET['body'];
    $table = 'post';
    $body = $_GET['body'];
    if(isset($_GET['privacy']))
    $privacy = $_GET['privacy'];
    else $privacy = 0;
    $sql = "INSERT INTO ".$table."(id_post, id_user,body,privacy_setting)
    VALUES (null, '$userid','$body','$privacy')";
    
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