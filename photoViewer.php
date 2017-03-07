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
    require ("includes/config.php");
    include_once "header.php";
    if (empty($_POST)) {
        $user_id = $_GET['id'];
        $photo_id = $_GET['photo_id'];
        $photoPath = $_GET['photoPath'];
        $caption = $_GET['caption'];
    }
    if (!empty($_POST) && !empty($_POST["addComment"])) {
        $user_id = $_POST['user_id'];
        $photo_id = $_POST['photo_id'];
        $photoPath = $_POST['photoPath'];
        $caption = $_POST['caption'];
        echo "WE ARE HERE";
        $sql_insert = "INSERT INTO photo_comment (id_photo, body, id_user) VALUES ('".$_POST['photo_id']."','".$_POST['comment']."','".$_POST['user_id']."');";
        $stmt = $conn->prepare($sql_insert);
        $stmt->execute();
    }
    if (!empty($_POST) && !empty($_POST["delete"])) {
        print_r($_POST);
        $user_id = $_POST['user_id'];
        $photo_id = $_POST['photo_id'];
        $photoPath = $_POST['photoPath'];
        $caption = $_POST['caption'];
        echo "tryna delete";
        print_r($_POST);
        $post_del = $_POST["id_del"];
        $sql_del = "DELETE FROM photo_comment  WHERE id_comment= ?";
        echo $sql_del;
        $stmt = $conn->prepare($sql_del);  
        $stmt->bindValue(1, $post_del);
        if (!$stmt){

            die('deleting failed');
        }
        else {
            echo " deleted photo successfully<br>";
            //delete the IMAGE FILE FROM THE 
            //UPLOAD FOLDER FILE PATH
        }
    } 

        ?>
 <div class="container-fluid">
    <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
              <h1><?php echo "your current picture"; ?></h1>
        </div>
        <div class="col-md-1">
        </div>
    </div>
    <div class="panel-body">
        <span>
            <img class="individualPhoto" src="<?php echo $photoPath?>" style"width:75px; height 75px;">
        </span>
        <span>
        <figcaption class="caption">Caption: <?php echo $caption?></figcaption>
        </span>
        <div class="clearfix"></div>
        <hr>
    <?php
        //echo "id_post:" . $row["id_post"]. "</br> userid: " . $row["id_user"]. "</br>body " . $row["body"]. "<br>";
        $com = "SELECT id_comment, id_photo, timestamp, body, id_user FROM photo_comment WHERE id_photo = ".$photo_id.' ORDER BY timestamp DESC';
        $res_com = $conn->query($com);
        while($row = $res_com->fetch(PDO::FETCH_ASSOC)){
            $names = "SELECT first_name, surname FROM user WHERE id_user =".$row["id_user"]." ";
            $commenter = $conn->query($names);
            $name = $commenter->fetch(PDO::FETCH_ASSOC);
            ?>
            <style>
            .commentRow {
                display: block;
                border: 2px solid black;
            }
            /*.comment{
                display: block;
                float: left;
                height: 32px;
                width: 100px;
            }

            .delComment{
                display: block;
                float: left;
                height: 40px;
                margin: -1px -2px -2px;
                width: 41px;
            }*/
            </style>
            <div class="commentRow">
                <div class="comment"><p align="left"> <?php echo $row["body"]?> </p>Posted By: <strong><?php echo $name["first_name"]." ".$name["surname"]?></strong> at : <?php echo $row["timestamp"]?></div>
            <div class="delComment">
                <form action="photoViewer.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="id_del" value="<?php echo $row["id_comment"]?>">
                                <input type="hidden" name="user_id" value="<?php echo $user_id;?>" >
                                <input type="hidden" name="photo_id" value="<?php echo $photo_id;?>" >
                                <input type="hidden" name="photoPath" value="<?php echo $photoPath;?>" >
                                <input type="hidden" name="caption" value="<?php echo $caption;?>" >
                                <p align="right"> <input type="submit" name="delete" value="x" /></p>
                            </form>
                        </div>
                    </div>
                    <br>
            <?php
        }
        echo "</br>";
    ?>
    <form action="photoViewer.php" method="post">
        <input type="hidden" name="user_id" value="<?php echo $user_id;?>" >
        <input type="hidden" name="photo_id" value="<?php echo $photo_id;?>" >
        <input type="hidden" name="photoPath" value="<?php echo $photoPath;?>" >
        <input type="hidden" name="caption" value="<?php echo $caption;?>" >
        Comment: <textarea name ="comment" rows="3" cols="30"></textarea>
        <input type="submit" name="addComment" value="Add Comment">
    </form>
    <form action="photoPage.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="profile" value="<?php echo $user_id?>">
        <input type="hidden" name="id_del" value="<?php echo $photo_id?>">
        <input type="submit" name="delete" value="Delete Photo">
    </form>
    </div> 
    <hr>


