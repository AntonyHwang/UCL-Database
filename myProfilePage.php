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
                <li><a href="#">Profile</a></li>
                <li><a href="#">Paris</a></li>
                <li><a href="#">Tokyo</a></li>
            </ul>
            </nav>

            <article>
                <h1>Profile</h1>
                <p>London is the capital city of England. It is the most populous city in the  United Kingdom, with a metropolitan area of over 13 million inhabitants.</p>
                <p>Standing on the River Thames, London has been a major settlement for two millennia, its history going back to its founding by the Romans, who named it Londinium.</p>
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
