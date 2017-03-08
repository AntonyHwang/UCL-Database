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
<link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
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
    if (!empty($_GET) && empty($_GET["delete"])) {
        try {
            if ($_GET['id'] == $_SESSION['id']) {
                $photoUploadLink = "uploadPhoto.php?uploadButton=upload";
                echo "<div style=\"float:right;\""."><a href=\"".$photoUploadLink." \"><button class=\"btn btn-primary\" >Upload Photo</button></a></div><br><br>";
                 
            }
            $user_id = $_GET['id'];
            $sql_select = ("SELECT * FROM photo WHERE id_user = '".$user_id."' ORDER BY id_photo DESC");
            $stmt = $conn->prepare($sql_select);
            $stmt->execute();
            $results = $stmt->fetchAll();

            ?>
            <div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10" style="text-align: center">
                      <h1>Your Photos</h1><br>
                </div>
                <div class="col-md-1">
                </div>
            </div>
            <?php
            foreach($results as $row) {
                    $photoViewLink = "photoViewer.php?id=".$user_id."&photoPath=".$row["file_path"]."&caption=".$row["body"]."&photo_id=".$row['id_photo'];
                    ?>
                    <div class="container-fluid" style="width:75%">
                        <div class="row" style="border-radius: 25px; background-color: #cae7f9;">
                            <div class="col-md-8" style="opacity: 4;">
                                <br>
                                <a href="<?php echo $photoViewLink ?>">
                                    <figure>
                                        <img class="center-block" style="max-width:100%;max-height:100%;"src="<?php echo $row["file_path"]?>">
                                        </a>
                                    </figure>
                                <a href="<?php echo $photoViewLink ?>"> 
                                </div>
                                <div class="col-md-4">
                                    <hr>
                                        <figcaption ><strong> Caption: </strong><?php echo $row["body"]?></figcaption>
                                        <hr>
                                    <br>
                                    <?php echo "<a href=\"".$photoViewLink." \"><button class=\"btn btn-primary\" >Add/View Comments</button></a><br><br>";
                                    $photoDeleteLink = "photoPage.php?profile=".$user_id."&id_del=".$row["id_photo"]."&del_path=".$row["file_path"];
                                    echo "<a href=\"".$photoDeleteLink." \"><button class=\"btn btn-warning\" >Delete Photo</button></a><br><br>";
                                    
                             ?>
                        </a>
                    </div>
                </div>
                <hr>
            </div>
        </div>
                <?php
            }
            ?>
        <?php
        }
        catch(Exception $e) {
            die(var_dump($e));
        }
    }
    if ($_GET["profile"] == $_SESSION["id"]) {
        $host = "eu-cdbr-azure-west-a.cloudapp.net";
        $user = "bd38b99b177044";
        $pwd = "5e59f1c8";
        $db = "blogster";
        // Connect to database.
        $conn;
        try {
            $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
            $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }
        catch(Exception $e){
            die(var_dump($e));
        }
        echo $_GET["profile"];
        print_r($_POST);
        $post_del = $_GET["id_del"];
        $sql_del = "DELETE FROM photo  WHERE id_photo = ? ";
        echo $sql_del;
        $stmt = $conn->prepare($sql_del);  
        $stmt->bindValue(1, $post_del);
        if (!$stmt->execute()){

            die('deleting failed');
        }
        else {
            echo " deleted photo successfully<br>";
            unlink($_GET["del_path"]);
            echo $_GET["del_path"];
            $refresh = $_GET['profile'];
            echo "<a href=\"photoPage.php?id=".$refresh." \"><button class=\"btn btn-primary\" >Return to Photos</button></a><br><br>";
        }
    }
    
?>

