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
	    $sql_circles = "SELECT name FROM circle WHERE id_user = '".$current_id."'";
	    $stmt = $conn->prepare($sql_friend);
	    $stmt->execute();
	    $circles = $stmt->fetchAll();
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
			<input name="selected[]" type="hidden" value="<?php echo $current_id?> ">
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
		$sql_insert = "INSERT INTO circle (id_user, name) VALUES (?,?)";
	    $stmt = $conn->prepare($sql_insert);
	    $stmt->bindValue(2, $_POST["circle_name"]);
		while ($i < $members) {
			$stmt->bindValue(1, $_POST["selected"][$i]);
			$stmt->execute();
			$i += 1;
		}
		header('Location:createCircle.php');
	}

?>

