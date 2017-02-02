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
<input></input>
<button>post</button>
<?php
//echo "Hello World!";

?>
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

$sql = "SELECT id_post, id_user, body FROM post";
$result = $conn->query($sql);

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
<?php 
for ($x = 0; $x <= 10; $x++) {?>
	<div class = 'post'>
	 
	 <p>body<p>
	 <p>comment</p>
	</div>

<?php } 
?>
<div></div>
</body>
</html>
<?php
/* CREATE TABLE `post` (
  `id_post` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `body` varchar(150) NOT NULL,
  `privacy_setting` int(3) NOT NULL
) */
?>
