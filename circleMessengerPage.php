<?php
	require ("includes/config.php");
	include_once "header.php";
	if (empty($_POST)) {
		$host = "eu-cdbr-azure-west-a.cloudapp.net";
	    $user = "bd38b99b177044";
	    $pwd = "5e59f1c8";
	    $db = "blogster";
	    // Connect to database.
	    try {
	        $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
	        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	    }
	    catch(Exception $e){
	        die(var_dump($e));
	    }
	    $current_id = $_SESSION['id'];
	    //retrieve all circles i'm a part of
	    $sql_circles = "SELECT member.id_circle, circle.name FROM circle JOIN member ON circle.id_circle = member.id_circle WHERE member.id_user = '".$current_id."' ";
	    $stmt = $conn->prepare($sql_circles);
	    $namestmt = $conn->prepare("SELECT first_name, surname FROM user WHERE '".$current_id."' = id_user ");
	    $namestmt->execute();
	    $name_array = $namestmt->fetch(PDO::FETCH_ASSOC);
	    $user_name = $name_array["first_name"]." ".$name_array["surname"];
	    echo "<!DOCTYPE html>
			<html>


			</head>
			<style>
			div {
			    
			    
			}
			.left{
				float: left;
				width:600px;
				margin: auto;
			    border: 1px solid blue;
			}
			.recm{
				float: right;
				margin: auto;
			    border: 1px solid blue;
			}
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
			 <h1> Circles </h1>";
			 $empty = true;
	    if ($stmt->execute()) {
	  //   	// print_r($stmt->fetchAll())''
	    	while ($circle_info = $stmt->fetch(PDO::FETCH_ASSOC)) {
	    		$empty = false;
	    		echo "<div class = 'left'> <h2>".$circle_info["name"]."</h2>";
	    	// 	//fetch all the messages
	    		$sql_circle_member = "SELECT timestamp, body FROM message WHERE '".$circle_info["id_circle"]."' = id_circle ORDER BY timestamp ASC ";
	    		$stmt2 = $conn->prepare($sql_circle_member);
	    		if ($stmt2->execute()) {
	    			while ($message = $stmt2->fetch(PDO::FETCH_ASSOC)) {
	    				echo '<div class="container-fluid">';
						echo '<div class="row">';
						echo '<div class="col-md-6">';
						echo substr($message["timestamp"], 5, 9);
						echo substr($message["timestamp"], -8, -3);
						echo '&nbsp;&nbsp;&nbsp;&nbsp;';
						echo $message["body"];
						echo "</div></div></div><br>";
	    			}
	    		}
	    		?>
	    			<div class = 'posts'>
				<div class="well"> 
				   <form class="form-horizontal" role="form" action="#" method="post">
				    <h4>Messenger</h4>
				     <div class="form-group" style="padding:14px;">
					<textarea class="form-control" placeholder="Message" name="message"></textarea>
					<input type="hidden" name="sender" value="<?php echo $user_name?>">
					<input type="hidden" name="id_circle" value="<?php echo $circle_info["id_circle"]?>">
					</div>
				    <button class="btn btn-primary pull-right" type="submit">Send</button>
				</form>
				</div>
			</div>
				    <?php
			}    
	    }
	    if ($empty) {
	    	echo "<h5>Looks like you have no circles to message!<h5>";
	    }
 		echo "</body></html>";
}
	else {
		//	insert new message into the database
		print_r($_POST);
		$host = "eu-cdbr-azure-west-a.cloudapp.net";
	    $user = "bd38b99b177044";
	    $pwd = "5e59f1c8";
	    $db = "blogster";
	    // Connect to database.
	    try {
	        $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
	        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	    }
	    catch(Exception $e){
	        die(var_dump($e));
	    }
		$sql_insert = "INSERT INTO message (id_message, id_circle, timestamp, body) VALUES (?,?,?,?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bindValue(1, NULL);
        $stmt->bindValue(2, $_POST["id_circle"]);
        $stmt->bindValue(3, NULL);
        $stmt->bindValue(4, $_POST["sender"].": ".$_POST["message"]);
        $stmt->execute();
        header('Location:circleMessengerPage.php');
	}
?>
<!-- Insert code into here -->