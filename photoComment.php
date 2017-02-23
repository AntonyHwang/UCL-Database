<?php
	ob_start();
    	$user_id = $_GET['user_id'];
    	$photo_id = $_GET['photo_id'];
    	$comment = $_GET['comment'];
    	$prevLink = $_GET['prevLink'];

    	$host = "eu-cdbr-azure-west-a.cloudapp.net";
        $user = "bd38b99b177044";
        $pwd = "5e59f1c8";
        $db = "blogster";
        try {
            $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
            $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }
        catch(Exception $e){
            die(var_dump($e));
        }
        $sql_insert = "INSERT INTO photo_comment (id_comment, id_photo, timestamp, body, id_user) VALUES (?,?,?,?,?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bindValue(1, NULL);
        $stmt->bindValue(2, $photo_id);
        $stmt->bindValue(3, NULL);
        $stmt->bindValue(4, $comment);
        $stmt->bindValue(5, $_SESSION['id'];
        $stmt = $conn->prepare($sql_insert);
        $stmt->execute();
        
    
?>