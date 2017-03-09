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
        $sql_del = "DELETE FROM photo_comment  WHERE id_comment = ?";
        echo $sql_del;
        $stmt = $conn->prepare($sql_del);  
        $stmt->bindValue(1, $post_del);
        if (!$stmt->execute()){

            die('deleting failed');
        }
        else {
            echo "deleted comment successfully<br>";
            $photoViewLink = "Location:photoViewer.php?id=".$user_id."&photoPath=".$photoPath."&caption=".$caption."&photo_id=".$photo_id;
            header($photoViewLink);
        }
    } 

        ?>
<div class="row">
                <div class="col-md-1">
                </div>
                <div class="col-md-10" style="text-align: center">
                      <h1>Your Current Photo</h1><br>
                </div>
                <div class="col-md-1">
                </div>
            </div>

                    <div class="container-fluid" style="width:75%">
                        <div class="row" style="border-radius: 25px; background-color: #cae7f9;">
                            <div class="col-md-8" style="opacity: 4;">
                                <br>
                                    <figure>
                                        <img class="center-block" style="max-width:100%;max-height:100%;"src="<?php echo $photoPath?>">
                                    </figure>
                                </div>
                                <div class="col-md-4">
                                    <hr>
                                        <figcaption ><strong> Caption: </strong><?php echo $caption?></figcaption>
                                        <hr>
                                    <br>
                                </div>
                            </div>

                                    <?php 
                                    $com = "SELECT id_comment, id_photo, timestamp, body, id_user FROM photo_comment WHERE id_photo = ".$photo_id.' ORDER BY timestamp DESC';
                                    $res_com = $conn->query($com);
                                    while($row = $res_com->fetch(PDO::FETCH_ASSOC)){
                                        $names = "SELECT first_name, surname FROM user WHERE id_user =".$row["id_user"]." ";
                                        $commenter = $conn->query($names);
                                        $name = $commenter->fetch(PDO::FETCH_ASSOC);?>
                                        <div class="row" style="border-radius: 25px; background-color: #cae7f9;">
                                            <div class="col-md-8"><p align="left"> <?php echo $row["body"]?> </p>Posted By: <strong><?php echo $name["first_name"]." ".$name["surname"]?></strong> at : <?php echo $row["timestamp"]?></div>
                                            <div class="col-md-4">
                                            <form action="photoViewer.php" method="post" enctype="multipart/form-data">
                                                <input type="hidden" name="id_del" value="<?php echo $row["id_comment"]?>">
                                                <input type="hidden" name="user_id" value="<?php echo $user_id;?>" >
                                                <input type="hidden" name="photo_id" value="<?php echo $photo_id;?>" >
                                                <input type="hidden" name="photoPath" value="<?php echo $photoPath;?>" >
                                                <input type="hidden" name="caption" value="<?php echo $caption;?>" >
                                                <p align="right"> <button class="btn btn-primary" type="submit" name="delete" value="x"/>x</button></p>
                                            </form>

                                                
                                            </div>
                                        </div>
                                    <?php

                                    }
                                    ?>
                                    <form action="photoViewer.php" method="post">
                                        <input type="hidden" name="user_id" value="<?php echo $user_id;?>" >
                                        <input type="hidden" name="photo_id" value="<?php echo $photo_id;?>" >
                                        <input type="hidden" name="photoPath" value="<?php echo $photoPath;?>" >
                                        <input type="hidden" name="caption" value="<?php echo $caption;?>" >
                                        <textarea name ="comment" rows="3" cols="30"></textarea>
                                        <button class="btn btn-primary" type="submit" name="addComment" value="Add Comment">Add Comment </button>
                                    </form>
                                    <?php 
                                    $photoDeleteLink = "photoPage.php?profile=".$user_id."&id_del=".$photo_id."&del_path=".$photoPath;
                                    echo "<a href=\"".$photoDeleteLink." \"><button class=\"btn btn-warning\" >Delete Photo</button></a><br><br>";
                                    ?>
                    </div>
                </div>
                <hr>
            </div>
        </div>



