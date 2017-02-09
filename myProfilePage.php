<?php ob_start(); 
    session_start();
?>
<html>
    <head>
        <link rel="stylesheet" type="css" href="./css/profile.css">
    </head>
    <body>
        <div class="container">

            <header>
            <h1><?php echo $_SESSION["user_firstname"]; ?> <?php echo $_SESSION["user_firstname"];?></h1>
            </header>
        
            <nav>
            <ul>
                <img src="<?php echo './uploads/'.$_SESSION["id"].$_SESSION["password"].'/profile.jpg'; ?>" alt="Profile Pic" style="width:120px;height 120px;">
            </ul>
            </nav>

            <article>
                <h1>Profile</h1>
                <h4>Gender:</h4>
                <h4>Email:</h4>
                <h4>Birthday:</h4>
            </article>
        </div>
    </body>

    <?php
        // configuration
        //require("includes/config.php"); 

        // DB connection info
        //TODO: Update the values for $host, $user, $pwd, and $db
        //using the values you retrieved earlier from the Azure Portal.
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
    ?>
</html>
