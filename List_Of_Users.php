<?php 
    require 'includes/config.php'; 
    include_once('adminheader.php');
?>

<?php 
$sql= "SELECT id_user, first_name,surname FROM user ";
$result = $conn->query($sql);

while($row = $result->fetch()) {
    $username= ucfirst($row["first_name"])." ".ucfirst($row["surname"]);
    $user_id= $row["id_user"];
   

?>    



        <h2>
    
<?php   
      
        echo "<img src= \"./uploads/".$user_id."/profile.jpg\" alt=\"Profile Pic\" style=\"width:50px; height 50px;\">";
        echo "".$username;
        echo "<a href=\"/profile.php?profile=".$row[id_user]."\" class=\"btn btn-success\">Profile Page</button></a>";

}       
?>