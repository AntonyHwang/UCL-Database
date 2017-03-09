<?php
	require ("includes/config.php");
	include_once "header.php";
?>

<style>
	.posts {
		width: 500px;
		margin: auto;
		
	}
</style>
<?php
	if (empty($_POST)) {
	    $current_id = $_SESSION['id'];
	    $sql_friend = "SELECT user.first_name, user.surname, user.id_user FROM user INNER JOIN friendship ON ((user.id_user = friendship.id_friend2 AND friendship.id_friend1 = '$current_id' ) OR (user.id_user = friendship.id_friend1 AND friendship.id_friend2 = '".$current_id."')) ORDER BY user.first_name";
	    $stmt = $conn->prepare($sql_friend);

	    if ($stmt->execute()) {
	    ?>
		<div class = 'posts'>
    	<div class="well"> 
		<form class="form-horizontal" action="createCircle.php" method="post">
			<h4>New Circle</h4>
			<div class="form-group" style="padding:14px;">
			<input class="form-control" name="circle_name" placeholder="Circle name"/>
			</br>Add members: </br>
			<?php
				while ($friend = $stmt->fetch(PDO::FETCH_ASSOC)) {
			?>
					<input name="selected[]" type="checkbox" value="<?php echo $friend["id_user"]?>"> <?php echo ucfirst($friend["first_name"])." ".ucfirst($friend["surname"]);?> <br>
			<?php
				}
			?> 
			
			</div>

			<input name="selected[]" type="hidden" value="<?php echo $_SESSION['id']?>">
			<button class="btn btn-primary pull-right" type="submit" name="create">Create</button><ul class="list-inline"><li><a href="photoPage.php?id=<?php echo $_SESSION['id']?>"><i class="glyphicon glyphicon-camera"></i></a>  Upload a New Photo</li></ul>
		</form>
    </div>

		<?php
	    }
	}

	else {
		$i = 0;
		$members = sizeof($_POST["selected"]);
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

