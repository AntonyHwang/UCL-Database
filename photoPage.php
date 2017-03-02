<style>
    div.panel-body {
        text-align: center;
        margin: auto;
        width: 500px;
        border: 5px solid black;
    }
    img.individualPhoto {
        width: 420px;
    }
    figcaption {
        height: auto;
    }
</style>
<?php 
    ob_start();
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
    if (!empty($_GET)) {
        try {
            if ($_GET['id'] == $_SESSION['id']) {
                ?>
                <form action="uploadPhoto.php" method="get" >
                    <input type="hidden" name="uploadButton" value="upload">
                    <input type="submit" value="Upload Photo">
                </form>
                <?php
            }
            $user_id = $_GET['id'];
            $sql_select = ("SELECT * FROM photo WHERE id_user = '".$user_id."' ORDER BY id_photo DESC");
            $stmt = $conn->prepare($sql_select);
            $stmt->execute();
            $results = $stmt->fetchAll();

            #ADD different photo rendering for circles/friends/privacy when we decide how to do that


            ?>
            <div class="container-fluid">
            <?php
            foreach($results as $row) {
                    $photoViewLink = "photoViewer.php?id=".$user_id."&photoPath=".$row["file_path"]."&caption=".$row["body"]."&photo_id=".$row['id_photo'];
                    echo $row["file_path"];
                    ?>
                        <div class="panel-body">
                        <a href="<?php echo $photoViewLink ?>">
                            <figure>
                                <img class="individualPhoto" src="<?php echo $row["file_path"]?>">
                                </a>
                                <hr><hr>
                                <figcaption>Caption: <?php echo $row["body"]?></figcaption>
                            </figure>
                        <a href="<?php echo $photoViewLink ?>"> 
                            <input type=button onClick="location.href='$photoViewLink'" value='Add/View Comments'>
                        </a>
                        </div>
                        <hr>
                <?php
            }
            ?>
        </div>
        <?php
            print_r($results);
        }
        catch(Exception $e) {
            die(var_dump($e));
        }
    }
    
?>

