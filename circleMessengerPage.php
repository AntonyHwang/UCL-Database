<?php
	require ("includes/config.php");
	include_once "header.php";

	$current_id = $_SESSION['id'];
    $sql_circles = "SELECT id_circle FROM member WHERE id_user = '".$current_id."' ";
    $stmt = $conn->prepare($sql_circles);
    $stmt->execute();
?>

<html>
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
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-1">
			</div>
				<div class="col-md-10">
					<h1>
						Circles
					</h1>
				</div>
			<div class="col-md-1">
			</div>
		</div>

<?php
	$circle_count = 0;
	if ($stmt->rowCount() > 0) {
		while ($circlename = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$circle_count++;
			$c_id = $circlename["id_circle"];
		    $sql_circle_name = "SELECT name, id_user FROM circle WHERE circle.id_circle = '".$c_id."'";
		    $stmt1 = $conn->prepare($sql_circle_name);
		    $stmt1->execute();
			$circle_row = $stmt1->fetch();
			$sql_owner_name = "SELECT first_name, surname FROM user WHERE id_user = ".$circle_row['id_user'];
			$stmt2 = $conn->prepare($sql_owner_name);
		    $stmt2->execute();
			$owner_row = $stmt2->fetch();
			$owner_name = ucfirst($owner_row['first_name'])." ".ucfirst($owner_row['surname']);
			if ($circle_count % 2 == 1) {
?>
		<div class="row">
			<div class="col-md-1">
			</div>
				<div class="col-md-5">
					<div class="jumbotron">
						<h2>
							<?php echo $circle_row['name']; ?>
						</h2>
						<p>
							<span class="glyphicon glyphicon-user"></span> <?php echo $owner_name; ?>
		
						</p>
						<p>
							<a class="btn btn-primary btn-large" href="#">Message Circle</a>
						</p>
					</div>
				</div>
	<?php } 
		if ($circle_count % 2 == 0) { ?>
					<div class="col-md-5">
						<div class="jumbotron">
							<h2>
								<?php echo $circle_row['name']; ?>
							</h2>
							<p>
								<span class="glyphicon glyphicon-user"></span> <?php echo $owner_name; ?>
			
							</p>
							<p>
								<a class="btn btn-primary btn-large" href="#">Message Circle</a>
							</p>
						</div>
					</div>
				<div class="col-md-1">
				</div>
			</div>

	<?php } 
		}
	}?>

	</div>
</html>

<?php
	if (empty($_POST)) {
	    $current_id = $_SESSION['id'];
	    //retrieve all circles i'm a part of
	    $sql_circles = "SELECT member.id_circle, circle.name FROM circle JOIN member ON circle.id_circle = member.id_circle WHERE member.id_user = '".$current_id."' ";
	    $stmt = $conn->prepare($sql_circles);
	    $namestmt = $conn->prepare("SELECT first_name, surname FROM user WHERE '".$current_id."' = id_user ");
	    $namestmt->execute();
	    $name_array = $namestmt->fetch(PDO::FETCH_ASSOC);
	    $user_name = $name_array["first_name"]." ".$name_array["surname"];
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













<?php
// First connect to the db and query for circles based on userid
	require ("includes/config.php");
	include_once "header.php";

    if (!empty($_GET) && !empty($_GET["del_user"])) {
        $del_circle = $_GET["del_circle"];
        $del_user = $_GET["del_user"];
        $sql_del = "DELETE FROM member WHERE id_user = ? AND id_circle = ?";
        $stmt_del = $conn->prepare($sql_del);
        $stmt_del->bindValue(1, $del_user);
        $stmt_del->bindValue(2, $del_circle);
        if ($stmt_del->execute()) {
        	echo "Member removed successfully";
        	//if only 1 
        	if ($_GET["last_mem"] == 1) {
        		$_GET["deleteCircle"] = 1;
        	}
        }
        else {
        	echo "Could not remove member";
        }

    }
    if (!empty($_GET) && !empty($_GET["deleteCircle"])) {
    	$del_circle = $_GET["del_circle"];
    	$sql_del_members = "DELETE FROM member WHERE id_circle = ? ";
    	$stmt_del_members = $conn->prepare($sql_del_members);
    	$stmt_del_members->bindValue(1, $del_circle);
    	if ($stmt_del_members->execute()) {
    		echo "members cleansed from circle";
    		$sql_del = "DELETE FROM circle WHERE id_circle = ?";
	        $stmt_del = $conn->prepare($sql_del);
	        $stmt_del->bindValue(1, $del_circle);
	        if ($stmt_del->execute()) {
	        	echo "Circle deleted successfully";
	        }
	        else {
	        	echo "Could not delete circle";
	        }
    	}
    	else {
    		echo "could not remove members from circle before deleting circle";
    	}

    }
    $current_id = $_SESSION['id'];
    $sql_circles = "SELECT id_circle FROM member WHERE id_user = '".$current_id."' ";
    $stmt = $conn->prepare($sql_circles);
    $stmt->execute();
    echo $stmt->rowCount();

echo "<!DOCTYPE html>
<html>


</head>
<style>
div {
    
    
}
.left{
	width:600px;
	margin: auto;
    border: 1px solid blue;
}
.recm{
	margin: auto;
    border: 1px solid blue;
}

</style>
<body>
<h1> Circles </h1>";

	if ($stmt->rowCount() > 0) {
		while ($circlename = $stmt->fetch(PDO::FETCH_ASSOC)) {
			print_r($circlename);
			$c_id = $circlename["id_circle"];
			$sql_circle_members = "SELECT user.first_name , user.surname, user.id_user FROM user INNER JOIN member ON user.id_user = member.id_user AND '".$c_id."' = member.id_circle";
	    	$stmt2 = $conn->prepare($sql_circle_members);
		    $stmt2->execute();
		    $sql_circle_name = "SELECT name FROM circle WHERE circle.id_circle = '".$c_id."'";
		    $stmt3 = $conn->prepare($sql_circle_name);
		    $stmt3->execute();
		    $memberCount = $stmt2->rowCount();
		    if ($memberCount > 0) {
		    	echo $memberCount;
		    	// print_r($stmt2->fetchAll());
		    	echo "<div class ='container-fluid' style='border-radius: 25px; background-color: #cae7f9;'> <h2>".$stmt3->fetch(PDO::FETCH_ASSOC)["name"]."</h2>";
		    	$index = 0;
		    	while ($circlemember = $stmt2->fetch(PDO::FETCH_ASSOC)) {
		    		// print_r($circlemember);
		    		$fullname = $circlemember["first_name"]." ".$circlemember["surname"];
		    		$member_id = $circlemember["id_user"];
		    		echo $fullname."-".$member_id;
		   //  		// echo 'id: '.$circlemember["first_name"]." ".$circlemember["surname"]' ';
					echo '<div class="row" style="border-radius: 25px; background-color: #cae7f9;">';
					echo '<div class="col-md-6">';?>
					<img src= "/uploads/<?php echo $member_id?>/profile.jpg" alt="Profile Pic" style="width:75px; height 75px;"><br>
    	 			<a href="./profile.php?profile=<?php echo $member_id?>"> <b><?php echo $fullname?></b></a>
    	 			<br>
                </div>
                <div class="col-md-6">
    	 			<?php
    	 			echo "<a href=\"./circlePage.php?del_user=".$member_id."&del_circle=".$c_id."&last_mem=".$memberCount." \"><button class=\"btn btn-warning\" >Remove</button></a>";
echo "</div></div>";
		    		$index += 1;
		    	}
		    	echo "<a href=\"./circlePage.php?del_circle=".$c_id."&deleteCircle=1 \"><button class=\"btn btn-warning\" >Delete Circle</button></a>";
		    	echo "</div><br>";
		    }
		    print_r($_POST);
		    // echo "\n";
		}
	}
echo "
</body>
</html>";
?>