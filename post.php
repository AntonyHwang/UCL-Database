<!DOCTYPE html>
<html>
<style>
div {
    width: 300px;
    margin: auto;
    border: 1px solid red;
}
</style>
<body>
<div>
<h1>posts</h1>
<form action="#" method="get">
<input name='body'></input>
<button type="submit">post</button>
</form>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blogster";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$userid = 12;

if (isset($_GET['body']) and $_GET['body']!=null){
	//echo $_GET['body'];
	$table = 'post';
	$body = $_GET['body'];
	echo $_GET['body'].'</br>';
	$sql = "INSERT INTO ".$table."(id_post, id_user,body,privacy_setting)
	VALUES (null, '$userid','$body',1)";
	if ($conn->query($sql) === TRUE) {
		echo"New post created successfully<br>";
		unset($_GET['body']);
	} else {
		echo"Error:". $sql ."<br>". $conn->error;
	}
	
}


$sql = "SELECT id_post, id_user, body FROM post WHERE id_user = ".$userid.' ORDER BY timestamp DESC';
$result = $conn->query($sql);
echo '</br>';
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
		echo "comment";
		echo "</br></br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

<div></div>
</body>
</html>
<?php

?>
