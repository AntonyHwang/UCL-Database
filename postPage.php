<?php
    require'includes/config.php';
    include_once('header.php');
?>

<h1>Your Own Posts</h1>

<?php 
$sql = "SELECT id_post, id_user, body FROM post WHERE id_user = ".$_SESSION["id"].' ORDER BY timestamp DESC';
$result = $conn->query($sql);

    while($row = $result->fetch()) {
        echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
		echo "</br></br>";
    }
?>
<br>
<br>

<h1>Friend's Post </h1>
<?php 


    while($row = $result->fetch()) {
        echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
		echo "comment";
		echo "</br></br>";
    }
?>

<h1>Friend of Friend's Post </h1>
<?php 
$sql = "SELECT id_post, id_user, body FROM post WHERE id_user = ".$_SESSION["id"].' ORDER BY timestamp DESC';
$result = $conn->query($sql);

    while($row = $result->fetch()) {
        echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
		echo "comment";
		echo "</br></br>";
    }
?>

<h1>Circle's Post </h1>
<?php 
$sql = "SELECT id_post, id_user, body FROM post WHERE id_user = ".$_SESSION["id"].' ORDER BY timestamp DESC';
$result = $conn->query($sql);

    while($row = $result->fetch()) {
        echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
		echo "comment";
		echo "</br></br>";
    }
?>

