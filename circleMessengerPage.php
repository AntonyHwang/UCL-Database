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
<script>
$(function () {
    var wtf = $('#scroll');
    var height = wtf[0].scrollHeight;
    wtf.scrollTop(height);
});
</script>

<style>

.me-chat-bubble{
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	float: right;
	color: white;
	display: inline-block;
	background-color:#3b5998;
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
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	float: left;
	color: black;
	display: inline-block;
	background-color:#DCDCDC;
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

 #scroll {
    height: 525px;
    overflow-y: scroll;
	overflow-x: hidden;
}
</style>

<div class="container-fluid">
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-7">
		<h1>
			<?php echo $c_name; ?>
		</h1>
		<hr>
		</div>
		<?php if ($_SESSION["id"] == $owner_id) { ?>
		<div class="col-md-3" align="right">
			<br><br>
			<form class="form-horizontal" role="form" action="" method="post">
				<button type="submit" class="btn btn-danger" name="del_circle" >Delete Circle</button></a>
			</form>
		</div>
		<?php } ?>
		<div class="col-md-1">
		</div>
	</div>
	<div class="row">
		<div class="col-md-1">
		</div>
		<div class="col-md-6">
			<div class="row">
				<div id="scroll">
				

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
													<?php $time = date($message_info["timestamp"]); 
															echo explode(' ', $time)[1]?>
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
													<?php $time = date($message_info["timestamp"]); 
															echo explode(' ', $time)[1]?>
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
			<div class="row">
				<div class = 'posts'>
					<div class="well"> 
					<form class="form-horizontal" role="form" action="circleMessengerPage.php?circle_id=<?php echo $_GET["circle_id"] ?>" method="post">
							<h4>Messenger</h4>
							<div class="form-group" style="padding:14px;">
							<textarea class="form-control" placeholder="Type your message here..." name="message"></textarea>
							</div>
							<button class="btn btn-primary pull-right" type="submit" name="send">Send</button>
							<br>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-1">
		</div>
		<div class="col-md-3">
			<div class="row" align="right">
				<h2>
					Members
				</h2>
				<hr>
			</div>
			<div class="row">
				<div id="scroll" style="height:375px">
				<?php
					$sql_circle_members = "SELECT user.first_name , user.surname, user.id_user FROM user JOIN member ON user.id_user = member.id_user AND '".$_GET["circle_id"]."' = member.id_circle ORDER BY user.first_name";
					$members = $conn->prepare($sql_circle_members);
					$members->execute();
					while ($member = $members->fetch()) {
						$mem_name = ucfirst($member["first_name"])." ".ucfirst($member["surname"]);
						$mem_id = $member["id_user"]; ?>
						<div class="row">
							<div class="col-md-7">
								&nbsp &nbsp &nbsp
								<img src= "/uploads/<?php echo $mem_id?>/profile.jpg" alt="Profile Pic" style="width:60px; height 60px;" class="img-rounded">
								<a href="./profile.php?profile=<?php echo $mem_id?>"> <b><?php echo " ".$mem_name?></b></a>
							</div>
							<div class="col-md-5" align="right">
								<form method="POST" action='circleMessengerPage.php?circle_id=<?php echo $_GET["circle_id"] ?>'>
									<?php if ($mem_id == $owner_id) { ?>
										<button class="btn btn-success" >Owner</button>
									<?php } else if ($mem_id == $_SESSION["id"]) { ?>
										<button type="submit" class="btn btn-warning" name="leave" >Leave Circle</button></a>
									<?php } else { ?>
										<button type="submit" class="btn btn-warning" name="remove" >Remove</button></a>
									<?php } ?>
									<input type="hidden" name="member_id" value="<?php echo $mem_id ?>" /> 
								</form>
							</div>
						</div>
						<hr>

					<?php
					}
				?>

				</div>
			</div>
			<div class="row" align="right">
				<h2>
					Add Members
				</h2>
				<hr>
			</div>
			<div class="row">
				<form class="form-horizontal" action="circleMessengerPage.php?circle_id=<?php echo $_GET["circle_id"] ?>" method="post">
					<div id="scroll" style="height:125px">
					<?php
						$current_id = $_SESSION['id'];
						$sql_friend = "SELECT user.first_name, user.surname, user.id_user FROM user INNER JOIN friendship ON ((user.id_user = friendship.id_friend2 AND friendship.id_friend1 = '$current_id' ) OR (user.id_user = friendship.id_friend1 AND friendship.id_friend2 = '".$current_id."')) ORDER BY user.first_name";
						$stmt = $conn->prepare($sql_friend);
						$stmt->execute();

						while ($friend = $stmt->fetch()) { 
							$sql_check = "SELECT id_user FROM member WHERE id_circle = ".$_GET["circle_id"]." AND id_user = ".$friend["id_user"];;
							$stmt1 = $conn->prepare($sql_check);
							$stmt1->execute();
							$rows = $stmt1->fetchAll();
							if (count($rows) == 0) { ?>
								<input name="selected[]" type="checkbox" value="<?php echo $friend["id_user"]?>"> <?php echo ucfirst($friend["first_name"])." ".ucfirst($friend["surname"]);?> <br>
						<?php
							}
						}
						?> 
					</div>
				<form method="POST" action=''>
					<button class="btn btn-primary pull-right" type="submit" name="add_members">Add selected</button>
				</form>
			</div>
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
		$stmt->execute();
		header('Location:circleMessengerPage.php?circle_id='.$_GET["circle_id"]);
	}
	if (isset($_POST['leave'])) {
		$id = $_POST["member_id"];
		$sql_del = "DELETE FROM member WHERE id_user = ".$id." AND id_circle = ".$_GET["circle_id"];
		$stmt = $conn->prepare($sql_del);
		$stmt->execute();
		header('Location:circlePage.php');
	}
	if (isset($_POST['remove'])) {
		$id = $_POST["member_id"];
		$sql_del = "DELETE FROM member WHERE id_user = ".$id." AND id_circle = ".$_GET["circle_id"];
		$stmt = $conn->prepare($sql_del);
		$stmt->execute();
		header('Location:circleMessengerPage.php?circle_id='.$_GET["circle_id"]);
	}
	if (isset($_POST['del_circle'])) {
		$sql_del_circle = "DELETE FROM circle WHERE id_circle = ".$_GET["circle_id"];
		echo $sql_del_circle;
		$stmt = $conn->prepare($sql_del_circle);
		$stmt->execute();
		header('Location:circlePage.php');
	}
	if (isset($_POST['add_members'])) {
		$sql_member_insert = "INSERT INTO member (id_circle, id_user) VALUES (?,?)";
		$stmt3 = $conn->prepare($sql_member_insert);
		$circle_id = $_GET["circle_id"];
		foreach($_POST['selected'] as $user) {
			$user_id = $user;
			$sql_member_insert = "INSERT INTO member (id_circle, id_user) VALUES (".$circle_id.",".$user_id.")";
			$stmt = $conn->prepare($sql_member_insert);
			$stmt->execute();
		}
		header('Location:circleMessengerPage.php?circle_id='.$_GET["circle_id"]);
	}
?>
