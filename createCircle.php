<?php
	require ("includes/config.php");
	include_once "header.php";

	if (empty($_POST)) {
	    $current_id = $_SESSION['id'];
	    $sql_friend = "SELECT user.first_name, user.surname, user.id_user FROM user INNER JOIN friendship ON ((user.id_user = friendship.id_friend2 AND friendship.id_friend1 = '$current_id' ) OR (user.id_user = friendship.id_friend1 AND friendship.id_friend2 = '".$current_id."'))";
	 //    echo "lol";
	    $stmt = $conn->prepare($sql_friend);
	    // $stmt->execute();
	    // $friend_list = $stmt->fetchAll();
	    // for ($friend_list as $friend) {
	    // 	echo $friend["first_name"]." ".$friend["surname"];
	    // }
	    if ($stmt->execute()) {
	    	?>
	    <form action="createCircle.php" method="post" enctype="multipart/form-data"> 
			<input name="circle_name" type="text">Circle Name<br>
	    	<?php
	    while ($friend = $stmt->fetch(PDO::FETCH_ASSOC)) {
			?>
			<input name="selected[]" type="checkbox" value="<?php echo $friend["id_user"]?>"> <?php echo $friend["first_name"]." ".$friend["surname"];?> <br>
				<?php
		}
		?>
			<input name="selected[]" type="hidden" value="<?php echo $_SESSION['id']?>">
			<input type="submit" value="Create Circle" name="create">
		</form>
		<?php
	    }
	}

	else {
		$i = 0;
		$members = sizeof($_POST["selected"]);
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
	    $sql_circle_check = "SELECT * FROM member INNER JOIN circle ON member.id_circle = circle.id_circle WHERE member.id_user = '".$_SESSION["id"]."' AND circle.name = '".$_POST["circle_name"]."'";
	    $circle_check = $conn->prepare($sql_circle_check);
	    $circle_check->execute();
	    if (count($circle_check->fetchAll()) > 0) {
	    	header('refresh:5; url=createCircle.php');
	    	echo "You are already part of a circle with that name. Try a different name. Redirecting to create circle page...";
	    }
	    else {
	    	$sql_insert = "INSERT INTO circle (id_user, name) VALUES (?,?)";
		    $stmt = $conn->prepare($sql_insert);
		    $stmt->bindValue(2, $_POST["circle_name"]);
		    $stmt->bindValue(1, $_SESSION["id"]);
		    $stmt->execute();
		    // $sql_members = "INSERT INTO circle (id_user, name) VALUES (?,?)";
		    $sql_circle_id = "SELECT id_circle FROM circle WHERE id_user = '".$_SESSION["id"]."' AND name = '".$_POST["circle_name"]."' ";
		    $stmt2 = $conn->prepare($sql_circle_id);
		    $stmt2->execute();
		    $sql_member_insert = "INSERT INTO member (id_circle, id_user) VALUES (?,?)";
		    $stmt3 = $conn->prepare($sql_member_insert);
		    // print_r($stmt2->fetchAll());
		    $i = 0;
		    $members = count($_POST["selected"]);
		    $circle_id = $stmt2->fetch(PDO::FETCH_ASSOC)["id_circle"];
		    $stmt3->bindValue(1, $circle_id);
		    while ($i < $members) {
		    	$stmt3->bindValue(2, $_POST["selected"][$i]);
		    	$stmt3->execute();
		    	$i += 1;
		    }
		    header('Location:circlePage.php');
	    }
	}

?>

