<?php 
    require('includes/config.php'); 
    include_once('header.php');

    $sql_select = "SELECT * FROM user WHERE id_user = '".$_SESSION["id"]."'";
    $stmt = $conn->query($sql_select);
    $row = $stmt->fetch();
    echo "<title>".ucfirst($row["first_name"])." ".ucfirst($row["surname"])."</title>";
    $email = $row["email"];
    $gender = $row["gender"];
    $dob = $row["dob"];
?>
<html>
    <body>
        <div class="container">
        
            <nav>
            <ul>
                <img src="<?php echo './uploads/'.$_SESSION["id"].'/profile.jpg'; ?>" alt="Profile Pic" style="width:120px;height 120px;">
            </ul>
            </nav>

            <article>
                <h1><?php echo ucfirst($row["first_name"])." ".ucfirst($row["surname"]);?></h1>
                <h4>Gender: <?php echo $gender;?></h4>
                <h4>Email: <?php echo $email;?></h4>
                <h4>Birthday:  <?php echo $dob;?></h4>
            </article>
        </div>
    </body>
</html>
