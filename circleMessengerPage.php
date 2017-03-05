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
	    $sql_circles = "SELECT name, id_circle FROM circle WHERE id_user = '".$current_id."' ";
	    $stmt = $conn->prepare($sql_circles);
	    if ($stmt->execute()) {
	    	?>
<style>
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
<div class = 'posts'>
<div class="well"> 
   <form class="form-horizontal" role="form" action="#" method="POST">
    <h4>Select a Circle to Message</h4>
     <div class="form-group" style="padding:14px;">
     	<?php
	    	while ($circle = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
	<input type="radio" name='id_circle' value="<?php echo $circle["id_circle"]?>"> <?php echo $circle["name"]?><br>
	<?php } ?>
	<textarea class="form-control" placeholder="Message" name="message"></textarea>
	</div>
	<input type="hidden" name='sender' value="<?php echo $_SESSION['id']?>">
    <button class="btn btn-primary pull-right" type="submit">Select</button>
    <?php
	    }
	    // Search for all messages associated with all circles, and render them in a list lol.


	}
	else {
		//	insert new message into the database
		print_r($_POST);
	}
?>
<!-- Insert code into here -->