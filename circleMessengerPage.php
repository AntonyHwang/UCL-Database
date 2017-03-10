<?php
	require ("includes/config.php");
	include_once "header.php";

	$c_id = $_GET['circle_id'];
    $sql_circles = "SELECT id_user, name FROM circle WHERE id_circle = '".$c_id."' ";
    $stmt = $conn->prepare($sql_circles);
    $stmt->execute();
	$row = $stmt->fetch();
	$owner_id = $row['id_user'];
	$c_name = $row['name'];
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-10">
		<h1>
			<?php echo $c_name; ?>
		</h1>
		</div>
		<div class="col-md-1">
		</div>
	</div>
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-6">
			<div class="row">
				<div class = 'posts'>
					<div class="well"> 
					<form class="form-horizontal" role="form" action="circleMessengerPage.php?circle_id=<?php echo $_GET["circle_id"] ?>" method="post">
							<h4>Messenger</h4>
							<div class="form-group" style="padding:14px;">
							<textarea class="form-control" placeholder="Type your message here..." name="message"></textarea>
							<input type="hidden" name="sender" value="<?php echo $user_name?>">
							<input type="hidden" name="id_circle" value="<?php echo $circle_info["id_circle"]?>">
							</div>
							<button class="btn btn-primary pull-right" type="submit" name="send">Send</button>
							<br>
						</form>
					</div>
				</div>
			</div>
			<div class="row">
				<div class = 'posts'>
					<div class="well"> 

					<?php
							$current_id = $_SESSION['id'];
							$messages_sql = "SELECT message.id_user, user.id_user, user.first_name, user.surname, message.timestamp, message.body FROM message JOIN user ON user.id_user = message.id_user WHERE message.id_circle = ".$_GET["circle_id"]." ORDER BY message.timestamp";
							$messages = $conn->prepare($messages_sql);
							//echo $messages_sql;
							$messages->execute();
							//echo $messages_sql;
							while ($message_info = $messages->fetch()) {
								//if ($message_info["id_user"] == $_SESSION['id']) {
								//	echo "<div>";
								//	echo "<img src= \"./uploads/".$message_info["id_user"]."/profile.jpg\" alt=\"Profile Pic\" style=\"width:40px; height 40px;\">";
								//	echo "&nbsp";
								//	echo "<a href=\"./profile.php?profile=".$message_info["id_user"]."\" >".$msg_username." </a>: ".$message_info["body"];
								//	echo "</div>";
								//	echo "<br>";
								//}
								$msg_username = ucfirst($message_info["first_name"])." ".ucfirst($message_info["surname"]);
								if ($message_info["id_user"] == $_SESSION["id"]) {
									echo "<div class=\"row\">";
										echo "<div class=\"col-md-2\">";
											echo "<div class=\"row\">";
												echo $message_info["timestamp"];
											echo "</div>";
											echo "<div class=\"row\">";
											echo "</div>";
										echo "</div>";
										echo "<div class=\"col-md-10\">";
											echo "<div class=\"row\">";
												echo "<div class=\"col-md-11\">";
													echo "<div class=\"row\">";
														echo "&nbsp <a>Me align=\"right\ </a>";
													echo "</div>";

													echo "<div class=\"row\">";
													echo "&nbsp &nbsp";
														echo $message_info["body"];
													echo "</div>";
												echo "</div>";
												echo "<div class=\"col-md-1\">";
													echo "<img src= \"./uploads/".$message_info["id_user"]."/profile.jpg\" alt=\"Profile Pic\" style=\"width:40px; height 40px;\" align=\"right\">";
												echo "</div>";
											echo "</div>";
										echo "</div>";
										echo "<br>";
										echo "<br>";
										echo "<br>";
									echo "</div>";
								}
								else {
									echo "<div class=\"row\">";
										echo "<div class=\"col-md-10\">";
											echo "<div class=\"row\">";
												echo "<div class=\"col-md-1\">";
													echo "<img src= \"./uploads/".$message_info["id_user"]."/profile.jpg\" alt=\"Profile Pic\" style=\"width:40px; height 40px;\">";
												echo "</div>";
												echo "<div class=\"col-md-11\">";
													echo "<div class=\"row\">";
														echo "&nbsp <a href=\"./profile.php?profile=".$message_info["id_user"]."\" >".$msg_username." </a>";
													echo "</div>";

													echo "<div class=\"row\">";
													echo "&nbsp &nbsp";
														echo $message_info["body"];
													echo "</div>";
												echo "</div>";
											echo "</div>";
										echo "</div>";
										echo "<div class=\"col-md-2\">";
											echo "<div class=\"row\">";
												echo $message_info["timestamp"];
											echo "</div>";
											echo "<div class=\"row\">";
											echo "</div>";
										echo "</div>";
										echo "<br>";
										echo "<br>";
										echo "<br>";
									echo "</div>";
								}
							}
					?>
				</div>
			</div>
		</div>
		<div class="col-md-4">
		</div>
		<div class="col-md-1">
		</div>
	</div>
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-10">
		</div>
		<div class="col-md-1">
		</div>
	</div>
</div>

<?php
	if (isset($_POST['send'])) {
		//	insert new message into the database
		$sql_insert = "INSERT INTO message (id_circle, id_user, timestamp, body) VALUES (".$_GET['circle_id'].", ".$_SESSION['id'].", NULL, \"".$_POST['message']."\")";
		$stmt = $conn->prepare($sql_insert);
		echo $sql_insert;
		$stmt->execute();
		echo $sql_insert;
		header('Location:circleMessengerPage.php?circle_id='.$_GET["circle_id"]);
	}
?>