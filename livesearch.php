<?php
    require('includes/config.php');     
    // Attempt search query execution
    try{
        if(isset($_REQUEST['term'])){
            // create prepared statement
            $sql = "SELECT id_user, first_name, surname FROM (SELECT first_name, surname, id_user, password, CONCAT(first_name,' ',surname) AS 'Con_Name' FROM user) AS x WHERE Con_Name LIKE :term";
            $stmt = $conn->prepare($sql);
            $term = $_REQUEST['term'] . '%';
            // bind parameters to statement
            $stmt->bindParam(':term', $term);
            // execute the prepared statement
            $stmt->execute();
            if($stmt->rowCount() > 0){
                while($row = $stmt->fetch()){
                    echo "<img src= \"./uploads/".$row[id_user]."/profile.jpg\" alt=\"Profile Pic\" style=\"width:75px; height 75px;\">"." ".ucfirst($row['first_name'])." ".ucfirst($row['surname'])."<br>";
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