<?php
    require'includes/config.php';
    try {
        $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        echo 'ok';
    }
    catch(Exception $e){
        die(var_dump($e));
    }
    
    $id1=32;
    $id2=91;
    $sql_insert = "INSERT INTO friend_request (id_from_user, id_to_user)VALUES ('".$id1."','".$id2."')";
    $in_friendship = "INSERT INTO friendship (id_friend1, id_friend2)
VALUES ('$id1', '$id2')";
    echo $sql_insert;
    $stmt = $conn->prepare($sql_insert);
    $stmt->execute();