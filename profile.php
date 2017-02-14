<?php 
    require 'includes/config.php'; 
    include_once('header.php');
	
    $sql_select = "SELECT * FROM user WHERE id_user = '".$_SESSION["id"]."'";
    $stmt = $conn->query($sql_select);
    $row = $stmt->fetch();
    $email = $row["email"];
    $gender = $row["gender"];
    $dob = $row["dob"];
	if (isset($_GET['profile']) and $_GET['profile']!=null){
		    $sql_select = "SELECT * FROM user WHERE id_user = '".$_GET['profile']."'";
			$stmt = $conn->query($sql_select);
			$row = $stmt->fetch();
			$email = $row["email"];
			$gender = $row["gender"];
			$dob = $row["dob"];
	}
	//$date->format('Y-m-d H:i:s')
?>
<html>
    <body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $("button").click(function(){
        alert("Value: " + $("#test").val());
		id = 51;
		window.location.href = "profile.php"+'?profile='+id;//$("#test").val();
    });
});
</script>
        <div class="container">
        
            <nav>
            <ul>
                <img src="<?php echo './uploads/'.$_SESSION["id"].$_SESSION["password"].'/profile.jpg'; ?>" alt="Profile Pic" style="width:120px;height 120px;">
            </ul>
            </nav>

            <article>
                <h1>Profile</h1>
                <h4>Gender: <?php echo $gender;?></h4>
                <h4>Email: <?php echo $email;?></h4>
                <h4>Birthday:  <?php echo $dob;?></h4>
            </article>
			<p>Name: <input type="text" id="test" value="Mickey Mouse"></p>

<button>Show Value</button>
        </div>
    </body>
</html>