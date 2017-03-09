

<?php
// First connect to the db and query for circles based on userid
	require ("includes/config.php");
	include_once "header.php";
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
		    	echo "<div class = 'left'> <h2>".$stmt3->fetch(PDO::FETCH_ASSOC)["name"]."</h2>";
		    	$index = 0;
		    	while ($circlemember = $stmt2->fetch(PDO::FETCH_ASSOC)) {
		    		// print_r($circlemember);
		    		$fullname = $circlemember["first_name"]." ".$circlemember["surname"];
		    		$member_id = $circlemember["id_user"];
		    		echo $fullname."-".$member_id;
		   //  		// echo 'id: '.$circlemember["first_name"]." ".$circlemember["surname"]' ';
					echo '<div class="container-fluid">';
					echo '<div class="row">';
					echo '<div class="col-md-6">';?>
					<img src= "/uploads/<?php echo $member_id?>/profile.jpg" alt="Profile Pic" style="width:75px; height 75px;"><br>
    	 			<a href="./profile.php?profile=<?php echo $member_id?>"> <b><?php echo $fullname?></b></a>
    	 			<br>
    	 			<?php
    	 			echo "<a href=\"./circlePage.php?del_user=".$member_id."&del_circle=".$c_id."&last_mem=".$memberCount." \"><button class=\"btn btn-warning\" >Remove</button></a>";
echo "</div></div></div>";
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
</div>
</body>
</html>";
?>

