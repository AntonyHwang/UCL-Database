

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
		    if ($stmt2->rowCount() > 0) {
		    	echo "<div class = 'left'> <h2>".$stmt3->fetch(PDO::FETCH_ASSOC)["name"]."</h2>";
		    	while ($circlemember = $stmt2->fetch(PDO::FETCH_ASSOC)) {
		    		// print_r($circlemember);
		    		$fullname = $circlemember["first_name"]." ".$circlemember["surname"];
		    		$member_id = $circlemember["id_user"];
		    		echo $fullname;
		   //  		// echo 'id: '.$circlemember["first_name"]." ".$circlemember["surname"]' ';
					echo '<div class="container-fluid">';
					echo '<div class="row">';
					echo '<div class="col-md-6">';?>
					<img src= "/uploads/<?php echo $member_id?>/profile.jpg" alt="Profile Pic" style="width:75px; height 75px;"><br>
    	 			<a href="./profile.php?profile=<?php echo $member_id?>"> <b><?php echo $fullname?></b></a>
    	 			<br>
    	 			<p align="right"><form action="#" method="post" enctype="multipart/form-data">
							<input type="hidden" name="del_user" value="<?php echo $member_id?>">
                                <input type="hidden" name="del_circle" value="<?php echo $c_id?>">
                                <input type="submit" name="removeMember" value="remove">
    	 			 </p>

		    		<?php echo "</div></div></div>";
		    	}
		    	?>
		    	<p align="left"><form action="#" method="post "enctype="multipart/form-data">
                                <input type="hidden" name="del_circle" value="<?php echo $c_id?>">
                                <input type="submit" name="deleteCircle" value="Delete Circle">
    	 			 </p>
    	 			 <?
		    	echo "</div><br>";
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

