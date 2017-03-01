

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
    $current_id = $_SESSION['id'];
    $sql_circles = "SELECT name FROM circle WHERE id_user = '".$current_id."' ";
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

</style>
<body>
<h1> Circles </h1>";

	if ($stmt->rowCount() > 0) {
		while ($circlename = $stmt->fetch(PDO::FETCH_ASSOC)) {
			print_r($circlename);
			$c_name = $circlename["name"];
			$sql_circle_members = "SELECT user.first_name , user.surname, user.id_user , circle.name FROM user INNER JOIN circle ON user.id_user = circle.id_user AND '".$c_name."' = circle.name";
	    	$stmt2 = $conn->prepare($sql_circle_members);
		    $stmt2->execute();
		    if ($stmt2->rowCount() > 0) {
		    	echo "<div class = 'left'> <h2>".$c_name."</h2>";
		    	while ($circlemember = $stmt2->fetch(PDO::FETCH_ASSOC)) {
		    		// print_r($circlemember);
		    		$fullname = $circlemember["first_name"]." ".$circlemember["surname"];
		    		$member_id = $circlemember["id_user"];
		    		echo $fullname;
		   //  		// echo 'id: '.$circlemember["first_name"]." ".$circlemember["surname"]' ';
					echo '<div class="container-fluid">';
					echo '<div class="row">';
					echo '<div class="col-md-6">';?>
					<img src= "/uploads/<?php echo $member_id?>/profile.jpg" alt="Profile Pic" style="width:75px; height 75px;">
    	 			<a href="./profile.php?profile=<?php echo $member_id?>"> <b><?php echo $fullname?></b></a>

		    		<?php echo "</div></div></div>";
		    	}
		    	echo "</div>";
		    }
		    print_r($circle_members);
		    // echo "\n";
		}
	}
echo "
</div>
</body>
</html>";
?>

