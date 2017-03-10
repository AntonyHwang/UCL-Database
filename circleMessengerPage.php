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

	$current_id = $_SESSION['id'];
?>
<style>
.me-chat-bubble{
	float: right;
	color: white;
	display: inline-block;
	background-color:#4169E1;
	align-self: flex-end;
	font-size: 16px;
	position: relative;
	display: inline-block;
	clear: both;
	margin-bottom: 4px;
	padding: 12px 12px;
	vertical-align: top;
	border-radius: 5px;
 }

 .you-chat-bubble{
	float: left;
	color: #4169E1;
	display: inline-block;
	background-color:white;
	align-self: flex-start;
	font-size: 16px;
	position: relative;
	display: inline-block;
	clear: both;
	margin-bottom: 4px;
	padding: 12px 12px;
	vertical-align: top;
	border-radius: 5px;
 }
</style>

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
							$messages_sql = "SELECT message.id_user, message.timestamp, user.id_user, user.first_name, user.surname, message.timestamp, message.body FROM message JOIN user ON user.id_user = message.id_user WHERE message.id_circle = ".$_GET["circle_id"]." ORDER BY message.timestamp";
							$messages = $conn->prepare($messages_sql);
							//echo $messages_sql;
							$messages->execute();
							//echo $messages_sql;
							$date = new DateTime('tomorrow');
							$prev_msg_date = $date->format('Y-m-d');

							while ($message_info = $messages->fetch()) {
								$current_msg_timestamp = strtotime($message_info["timestamp"]);
								$current_msg_date = date('Y-m-d', $current_msg_timestamp);
								if ($current_msg_date != $prev_msg_date) { ?>
									<div class="row" align="center">
										<br>
										<strong>
										<?php echo $current_msg_date; ?>
										</strong>
									</div>
								<?php }
								$msg_username = ucfirst($message_info["first_name"])." ".ucfirst($message_info["surname"]);
								
								if ($message_info["id_user"] == $_SESSION["id"]) {?>
									<div class="row">
										<div class="col-md-2">
											<div class="row">
												<div class="col-md-12">
													<?php echo $time = date('H:i', $message_info["timestamp"]); ?>
												</div>
											</div>
										</div>
										<div class="col-md-10">
											<div class="row">
												<div class="col-md-10">
													<div class="row"align="right">
														<div class="col-md-12">
															<?php echo ""; ?>
														</div>
													</div>
													<div class="row" align="right">
														<div class="col-md-12">
														<div class="me-chat-bubble-wrap">
															<div class="me-chat-bubble">
																<?php echo $message_info["body"]; ?>
															</div>
														</div>
														</div>
													</div>
												</div>
												<div class="col-md-2">
													<div class="row">
														<div class="col-md-12">
															<?php echo "<img src= \"./uploads/".$message_info["id_user"]."/profile.jpg\" alt=\"Profile Pic\" class=\"img-rounded\" style=\"width:60px; height 60px;\">"; ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<br>
							<?php } else { ?>
									<div class="row">
										<div class="col-md-10">
											<div class="row">
												<div class="col-md-2">
													<?php echo "<img src= \"./uploads/".$message_info["id_user"]."/profile.jpg\" alt=\"Profile Pic\" class=\"img-rounded\" style=\"width:60px; height 60px;\">"; ?>
												</div>
												<div class="col-md-10">
													<div class="row" align="left">
														<div class="col-md-12">
															<?php echo "<a href=\"./profile.php?profile=".$message_info["id_user"]."\" >".$msg_username." </a>"; ?>
														</div>
													</div>
													<div class="row" align="left">
														<div class="col-md-12">
															<div class="you-chat-bubble-wrap">
																<div class="you-chat-bubble">
																	<?php echo $message_info["body"]; ?>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class="row">
												<div class="col-md-12">
													<?php echo $time = date('H:i', $message_info["timestamp"]); ?>
												</div>
											</div>
										</div>
									</div>
									<br>
						<?php		}
							$prev_msg_date = $current_msg_date;
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