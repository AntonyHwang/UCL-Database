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
            $host = "eu-cdbr-azure-west-a.cloudapp.net";
            $user = "bd38b99b177044";
            $pwd = "5e59f1c8";
            $db = "blogster";
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "blogster";

// Create connection
            try {
                $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
                $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
				echo 'ok';
            }
            catch(Exception $e){
                die(var_dump($e));
            }
			
//$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
// if ($conn->connect_error) {
// 	echo 'problem in con';
//     die("Connection vvvv: " . $conn->connect_error);
// } 
$userid = 12;

if (isset($_GET['body']) and $_GET['body']!=null){
	//echo $_GET['body'];
	$table = 'post';
	$body = $_GET['body'];
	echo $_GET['body'].'</br>';
	$sql = "INSERT INTO ".$table."(id_post, id_user,body,privacy_setting)
	VALUES (null, '$userid','$body',1)";
	// if ($conn->query($sql) === TRUE) {
	// 	echo"New post created successfully<br>";
	// 	unset($_GET['body']);
	// } else {
	// 	echo"Error:". $sql ."<br>". $conn->error;
	// }
	 $stmt = $conn->query($sql);  
	if (!$stmt){
		die('post failed');
		}
	else {
	echo"New post created successfully<br>";
	}
	
}


$sql = "SELECT id_post, id_user, body FROM post WHERE id_user = ".$userid.' ORDER BY timestamp DESC';
$result = $conn->query($sql);
echo 'get post';//.$result->num_rows;

    while($row = $result->fetch()) {
        echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
		echo "comment";
		echo "</br></br>";
    }

//$conn->close();
?>

<div></div>
</body>
</html>
<?php

?>
