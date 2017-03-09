<?php
    require('includes/config.php');     
    // Attempt search query execution
    try{
        if(isset($_REQUEST['term'])){
            $type = $_REQUEST['type'];
            // create prepared statement
            if ($type == "all") {
                $sql = "SELECT id_user, first_name, surname FROM 
                        (SELECT first_name, surname, id_user, password, CONCAT(first_name,' ',surname) AS 'Con_Name' FROM user WHERE id_user <> '".$_SESSION["id"]."') AS x 
                        WHERE Con_Name LIKE :term";
            }
            else if ($type == "friends") {
                $sql = "SELECT id_user, first_name, surname FROM 
                        (SELECT first_name, surname, id_user, password, CONCAT(first_name,' ',surname) AS 'Con_Name' FROM user) AS x 
                        WHERE Con_Name LIKE :term AND id_user IN (SELECT id_friend2 FROM friendship WHERE id_friend1 = '".$_SESSION["id"]."' UNION SELECT id_friend1 FROM friendship WHERE id_friend2 = '".$_SESSION["id"]."')";
            }
            $stmt = $conn->prepare($sql);
            $term = $_REQUEST['term'] . '%';
            // bind parameters to statement
            $stmt->bindParam(':term', $term);
            // execute the prepared statement
            $stmt->execute();
            if($stmt->rowCount() > 0){
                while($row = $stmt->fetch()){
                    echo "<a href=\"./profile.php?profile=".$row[id_user]."\"> <img src= \"./uploads/".$row[id_user]."/profile.jpg\" alt=\"Profile Pic\" style=\"width:75px; height 75px;\">"." ".ucfirst($row['first_name'])." ".ucfirst($row['surname'])."<br>";
                }
            } else{
                echo "<p>No matches found";
            }
        }  
    } catch(PDOException $error){
        die("ERROR: Could not able to execute $sql. " . $error->getMessage());
    }
    
    // Close connection
    unset($pdo)
?>