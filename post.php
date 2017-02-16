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
    border: 1px solid red;
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
<body id=grad>
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

<h1>posts</h1>


<?php

/*             $host = "eu-cdbr-azure-west-a.cloudapp.net";
            $user = "bd38b99b177044";
            $pwd = "5e59f1c8";
            $db = "blogster"; */
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "blogster";

// Create connection
            try {
                $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
                $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				echo 'ok';
            }
            catch(Exception $e){
                die(var_dump($e));
            }
			
//$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
// if ($conn->connect_error) {
// 	echo 'problem in con';
//     die("Connection vvvv: " . $conn->connect_error);
// } 
$_SESSION["id"]=61;
$userid = $_SESSION["id"];
//handle post 
if (isset($_GET['body']) and $_GET['body']!=null){
	//echo $_GET['body'];
	$table = 'post';
	$body = $_GET['body'];
	if(isset($_GET['privacy']))
	$privacy = $_GET['privacy'];
	else $privacy = 0;
	
	echo $_GET['body'].'</br>';
	$sql = "INSERT INTO ".$table."(id_post, id_user,body,privacy_setting)
	VALUES (null, '$userid','$body','$privacy')";
	// if ($conn->query($sql) === TRUE) {
	// 	echo"New post created successfully<br>";
	// 	unset($_GET['body']);
	// } else {
	// 	echo"Error:". $sql ."<br>". $conn->error;
	// }
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
//handle comment
if (isset($_GET['comment']) and $_GET['comment']!=null and isset($_GET['postid'])){
	//echo $_GET['body'];
	$table = 'post_comment';
	$body = $_GET['comment'];
	$postid = $_GET['postid'];
	
	
	
	$sql = "INSERT INTO ".$table."(id_comment,id_post, id_user,body)
	VALUES (null, '$postid','$userid','$body')";
	// if ($conn->query($sql) === TRUE) {
	// 	echo"New post created successfully<br>";
	// 	unset($_GET['body']);
	// } else {
	// 	echo"Error:". $sql ."<br>". $conn->error;
	// }
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
$sql = "SELECT id_post, id_user, body FROM post WHERE id_user = ".$userid.' ORDER BY timestamp DESC';
$result = $conn->query($sql);
echo 'get post';//.$result->num_rows;

    while($row = $result->fetch()) {
		
		?>
		<div class="panel-body">
	<?php echo $row['body'];?>
	<div class="clearfix"></div>
	<hr>
	
	
				<?php
       //echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
		$com = "SELECT id_post, id_user,id_comment, body,timestamp FROM post_comment WHERE id_post = ". $row["id_post"].' ORDER BY timestamp DESC';

		$res_com = $conn->query($com);
		while($row = $res_com->fetch()){
			echo "userid/name: " . $row["id_user"]. "body " . $row["body"]. " at ".$row["timestamp"]."</br>";
		}
		echo "</br>";
    


?>	

	
	
	<hr>
	
	<form  action = '#' method="get">
	<div class="input-group">
	  <div class="input-group-btn">
	  <button class="btn btn-default"><i class="glyphicon glyphicon-share"></i></button>
	  </div>
	  <input type="hidden" name="postid" value="<?php echo $row["id_post"]; ?>" >
	  <input type="text" name = 'comment' class="form-control" placeholder="Add a comment..">
	</div>
	</form>
	

	
	</div>
	<?php
	}
	?>


</body>
</html>
