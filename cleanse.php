<?php
	require ("includes/config.php");
	include_once "header.php";
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
    $sql_circles = "DELETE FROM circle; DELETE FROM member";
    $stmt = $conn->prepare($sql_circles);
    if ($stmt->execute()) {
    	echo "lol";
    	$sql_circles = "SELECT name, id_circle FROM circle WHERE id_user = '".$current_id."' ";
	    $stmt2 = $conn->prepare($sql_circles);
	    if ($stmt2->execute()) {
	    	print_r($stmt2->fetchAll());
	    }
    }
 ?>