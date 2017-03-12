<?php 
    require'includes/config.php';
    include_once('header.php');

    $user_id = $_POST['user_id'];
    $photo_id = $_POST['photo_id'];
    $photoPath = $_POST['photoPath'];
    $caption = $_POST['caption'];

    if (!empty($_POST) && !empty($_POST["addComment"])) {
        $user_id = $_POST['user_id'];
        $photo_id = $_POST['photo_id'];
        $photoPath = $_POST['photoPath'];
        $caption = $_POST['caption'];
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

<script>
$(function () {
    var wtf = $('#scroll');
    var height = wtf[0].scrollHeight;
    wtf.scrollTop(height);
});
</script>

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

    #scroll {
    height: 525px;
    overflow-y: scroll;
	overflow-x: hidden;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
            <h1>
                Photo
            </h1>
            <hr>
        </div>
    </div>
	<div class="row">
        <div class="col-md-1">
        </div>
		<div class="col-md-10">	
            <div class="col-md-8">
                <div class="thumbnail">
                    <br>
                    <img class="center-block" style="max-width:100%;max-height:100%;"src="<?php echo $photoPath;?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                                            <div align="right">
                            <?php                 $_photoOwner =  "SELECT id_photo, id_user FROM photo WHERE  id_photo = ".$photo_id;
                                $ownerres = $conn->query($_photoOwner);
                                 $row = $ownerres->fetch();
                                 $photoOwner=$row[1];
                             if($_SESSION["user_type"] == "ADMIN"||$photoOwner==$_SESSION["id"]){
                                    $photoDeleteLink = "photoPage.php?profile=".$user_id."&id_del=".$photo_id."&del_path=".$photoPath;
                                    echo "<a href=\"".$photoDeleteLink." \"><button class=\"btn btn-danger\" >Delete Photo</button></a><br><br>";
                            }?>
                        </div>
                    <div class="caption">
                        <h3>
                            &nbsp
                            <?php echo $caption ?>
                        </h3>
                        <br>
                        <p>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <h3>
                        &nbsp Comments
                    </h3>
                    <hr>
                    <div style="max-height:500px;" id="scroll">
                        <?php                                  
                            $com = "SELECT id_comment, id_photo, timestamp, body, id_user FROM photo_comment WHERE id_photo = ".$photo_id.' ORDER BY timestamp';
                            $res_com = $conn->query($com);
                            while($row = $res_com->fetch(PDO::FETCH_ASSOC)){
                                $names = "SELECT id_user, first_name, surname FROM user WHERE id_user =".$row["id_user"]." ";
                                $commenter = $conn->query($names);
                                $name = $commenter->fetch(PDO::FETCH_ASSOC);?>
                                <div class="row">
                                    <div class="col-md-3">
                                        &nbsp &nbsp &nbsp
                                        <?php echo "<img src= \"./uploads/".$name["id_user"]."/profile.jpg\" alt=\"Profile Pic\" class=\"img-rounded\" style=\"width:60px; height 60px;\">"; ?>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row">
                                            <a href="./profile.php?profile="<?php echo $name["id_user"]?>> <?php echo ucfirst($name["first_name"])." ".ucfirst($name["surname"])?> </a>
                                        </div>
                                        <div class="row">
                                            <?php echo $row["body"]; ?>
                                            <div align="right">
                                                <?php $time = date($row["timestamp"]);?>
												<small><?php echo explode(' ', $time)[1];?>&nbsp &nbsp &nbsp &nbsp</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                            <?php
                            }
                            ?>
                    </div>
                    <div>
                                    <form action="photoViewer.php" method="post">
                                        <input type="hidden" name="user_id" value="<?php echo $_SESSION["id"];?>" >
                                        <input type="hidden" name="photo_id" value="<?php echo $photo_id;?>" >
                                        <input type="hidden" name="photoPath" value="<?php echo $photoPath;?>" >
                                        <input type="hidden" name="caption" value="<?php echo $caption;?>" >
                                        <div class="form-group">
                                        <textarea class="form-control" placeholder="Type your message here..." name="comment"></textarea>
                                        </div>
                                        <div align="right">
                                            <button class="btn btn-primary" type="submit" name="addComment" value="Add Comment">Add Comment </button>
                                        </div>
                                    </form>
                </div>
            </div>			
		</div>
        <div class="col-md-1">
        </div>
	</div>
</div>


