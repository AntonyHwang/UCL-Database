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
    require ("includes/config.php");
    include_once "header.php";
        echo "LOL";
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
        //Add an add-comment feature, as well as render comments
        //in photopage, maybe show number of comments or few comments
        //if they want ot add from photopage, link them to photoViewer
    if (!empty($_POST)) {
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
        $user_id = $_POST['user_id'];
        $photo_id = $_POST['photo_id'];
        $photoPath = $_POST['photoPath'];
        $caption = $_POST['caption'];
        echo "WE ARE HERE";
        $sql_insert = "INSERT INTO photo_comment (id_photo, body, id_user) VALUES ('".$_POST['photo_id']."','".$_POST['comment']."','".$_POST['user_id']."');";
        echo $sql_insert;
        // $sql_insert = "INSERT INTO user (first_name, surname, email, password, gender, dob, privacy_setting)VALUES ('".$first_name."','".$surname."','".$email."','".$password."','".$gender."','".$dob."', 0);";
                
        $stmt = $conn->prepare($sql_insert);
        $stmt->execute();
    }

$sqlComment = "SELECT id_photo, timestamp, body, id_user FROM photo_comment WHERE id_photo = ".$_photo_id.' ORDER BY timestamp DESC';
$sqlUser= "SELECT first_name,surname FROM user WHERE id_user = ".$_SESSION["id"].' ';
$result = $conn->query($sql);
$result2= $conn->query($sql2);
while($row2 = $result2->fetch()) {
    $username= $row2["first_name"]." ".$row2["surname"];
}
    while($row = $result->fetch()) {
        $postid = $row["id_post"];

        ?>
 
        <div class="panel-body">
        <h2>    
        <?php
        echo $username;
        ?>
        </h2>
        <paragraph>
        <?php
        echo $row["body"];
        ?>
        </paragraph>
        <br>
        <div class="clearfix"></div>
        <hr>
    <?php
        //echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
        $com = "SELECT id_post, id_user,id_comment, body,timestamp FROM post_comment WHERE id_post = ". $row["id_post"].' ORDER BY timestamp DESC';
        $res_com = $conn->query($com);
        while($row = $res_com->fetch()){
        echo "userid/name: " . $row["id_user"]. " " . $row["body"]. " at ".$row["timestamp"]."</br>";
        }
        echo "</br>";
    ?>
    <form  action = '#' method="get">
    <div class="input-group">
    <div class="input-group-btn">
    <button class="btn btn-default"><i class="glyphicon glyphicon-share"></i></button>
    </div>
    <input type="hidden" name="postid" value="<?php echo $postid; ?>" />
    <input type="text" name = 'comment' class="form-control" placeholder="Add a comment..">
    </div>
    </form>
    </div>
    <hr>
    <?php
    echo "</br></br>";
    }
    ?>


?>

<div class="individualPhoto">
            <span>
                <img class="individualPhoto" src="<?php echo $photoPath?>">
            </span>
            <span>
                <figcaption class="caption"><?php echo $caption?></figcaption>

                <form action="photoViewer.php" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>" >
                    <input type="hidden" name="photo_id" value="<?php echo $photo_id;?>" >
                    <input type="hidden" name="photoPath" value="<?php echo $photoPath;?>" >
                    <input type="hidden" name="caption" value="<?php echo $caption;?>" >
                    Comment: <textarea name ="comment" rows="3" cols="30"></textarea>
                    <input type="hidden" name="prevLink" value="<?php echo $_SERVER['REQUEST_URI'];?>">
                    <input type="submit" value="Add Comment">
                </form>

            </span>
            
        </div>