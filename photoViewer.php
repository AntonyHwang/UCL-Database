<style>
    div.individualPhoto {
    	text-align: center;
    	margin: auto;
        width: 800px;
        border: 5px solid blue;
    }
    img.individualPhoto {
        width: 420px;
        text-align: left;
    }
    figcaption.caption {
        height: auto;
        border: 5px solid yellow;
    }
    figcaption.comment {
        height: auto;
        border: 1px solid black;
    }
</style>
<?php 
    ob_start();
    require ("includes/config.php");
    include_once "header.php";
    if (!empty($_GET)) {
    	$user_id = $_GET['id'];
        $photo_id = $_GET['photo_id'];
    	$photoPath = $_GET['photoPath'];
    	$caption = $_GET['caption'];
        // $host = "eu-cdbr-azure-west-a.cloudapp.net";
        // $user = "bd38b99b177044";
        // $pwd = "5e59f1c8";
        // $db = "blogster";
        // try {
        //     $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
        //     $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        // }
        // catch(Exception $e){
        //     die(var_dump($e));
        // }
        // $sql_select = ("SELECT * FROM comment WHERE id_photo = '".$photo_id."'");
        // $stmt = $conn->prepare($sql_select);
        // $stmt->execute();
        // $results = $stmt->fetchAll();
    	?>
    	<div class="individualPhoto">
            <span>
                <img class="individualPhoto" src="<?php echo $photoPath?>">
            </span>
            <span>
                <figcaption class="caption"><?php echo $caption?></figcaption>
                <form action="photoComment.php" method="GET" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?php echo $user_id?>">
                    <input type="hidden" name="photo_id" value="<?php echo $photo_id?>">
                    Comment: <textarea name ="comment" rows="3" cols="30"></textarea>
                    <input type="hidden" name="prevLink" value="<?php echo $_SERVER['REQUEST_URI']?>">
                    <input type="submit" value="Add Comment">
                </form>
            </span>
            
        </div>
        <?php
        //Add an add-comment feature, as well as render comments
        //in photopage, maybe show number of comments or few comments
        //if they want ot add from photopage, link them to photoViewer
    }

?>