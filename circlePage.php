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
